<?php
/**
 * Plugin Name: TEC – Sort Multi-Day Events by End Date
 * Plugin URI:  https://github.com/soderlind/tec-sort-by-end-date
 * Description: Makes The Events Calendar sort events by end date (ascending) instead of start date.
 *              Useful when you have multi-day events and want the one ending latest to appear last.
 * Version:     1.1.1
 * Author:      Per Soderlind
 * Author URI:  https://soderlind.no
 * License:     GPL-2.0+
 * Requires Plugins: the-events-calendar
 */

declare(strict_types=1);

namespace Soderlind\TEC\SortByEndDate;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

if ( class_exists( \Soderlind\WordPress\GitHubUpdater::class ) ) {
	\Soderlind\WordPress\GitHubUpdater::init(
		github_url:  'https://github.com/soderlind/tec-sort-by-end-date',
		plugin_file: __FILE__,
		plugin_slug: 'tec-sort-by-end-date',
		name_regex:  '/tec-sort-by-end-date\.zip/',
		branch:      'main',
	);
}

/**
 * Hook into posts_clauses so we can rewrite the ORDER BY clause.
 *
 * TEC 6.x uses the `tec_occurrences` table with `start_date` and `end_date` columns.
 * We detect TEC queries by checking if the occurrences table is JOINed,
 * then replace `start_date` ordering with `end_date`.
 *
 * Priority 200 — runs after TEC's own filters.
 */
add_filter( 'posts_clauses', __NAMESPACE__ . '\sort_events_by_end_date', 200, 2 );

/**
 * Replace the ORDER BY clause for front-end event queries.
 *
 * @param array     $clauses  Associative array of SQL clauses.
 * @param \WP_Query $query    Current query object.
 *
 * @return array Modified clauses.
 */
function sort_events_by_end_date( array $clauses, \WP_Query $query ): array {

	// Only touch event queries, and only on the front end.
	if ( is_admin() ) {
		return $clauses;
	}

	// Detect TEC event queries by post_type.
	$post_type = $query->get( 'post_type' );
	$is_event  = $post_type === 'tribe_events'
		|| ( is_array( $post_type ) && in_array( 'tribe_events', $post_type, true ) );

	if ( ! $is_event ) {
		return $clauses;
	}

	// Verify that the tec_occurrences table is JOINed (TEC 6.x).
	if ( empty( $clauses[ 'join' ] ) || strpos( $clauses[ 'join' ], 'tec_occurrences' ) === false ) {
		return $clauses;
	}

	/**
	 * Filter: tec_sort_by_end_date_order
	 *
	 * Controls the sort direction. Default 'ASC' — events ending soonest first.
	 * Use 'DESC' to put the event ending latest at the top.
	 *
	 * @param string    $order   'ASC' or 'DESC'.
	 * @param \WP_Query $query   Current query object.
	 */
	$order = apply_filters( 'tec_sort_by_end_date_order', 'ASC', $query );
	$order = strtoupper( $order ) === 'DESC' ? 'DESC' : 'ASC';

	// Replace start_date ordering with end_date in the ORDER BY clause.
	// TEC uses formats like: `wp_XX_tec_occurrences.start_date ASC` or `event_date ASC`.
	$orderby = $clauses[ 'orderby' ] ?? '';

	// Replace direct table.start_date references.
	$orderby = preg_replace(
		'/(\w+_tec_occurrences)\.start_date\s+(ASC|DESC)/i',
		'$1.end_date ' . $order,
		$orderby
	);

	// Replace the event_date alias if it's used (it aliases start_date).
	// We need to add our own end_date to the fields clause for this to work.
	if ( preg_match( '/\bevent_date\s+(ASC|DESC)/i', $orderby ) ) {
		// Extract the table name from the JOIN clause.
		if ( preg_match( '/JOIN\s+(\w+_tec_occurrences)\s+ON/i', $clauses[ 'join' ], $matches ) ) {
			$table   = $matches[ 1 ];
			$orderby = preg_replace(
				'/\bevent_date\s+(ASC|DESC)/i',
				"{$table}.end_date {$order}",
				$orderby
			);
		}
	}

	$clauses[ 'orderby' ] = $orderby;

	return $clauses;
}
