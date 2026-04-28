=== TEC – Sort Multi-Day Events by End Date ===
Contributors: persoderlind
Tags: events, the-events-calendar, sorting, multi-day
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPL-2.0+
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Sort The Events Calendar events by end date instead of start date.

== Description ==

Makes The Events Calendar sort events by end date (ascending) instead of start date. Useful when you have multi-day events and want the one ending soonest to appear first.

**Example:** You have two events:
* "Hele mai" — runs May 1–31
* "midten av mai" — runs May 4–17

By default, TEC sorts by start date, showing "Hele mai" first. With this plugin, "midten av mai" appears first because it ends sooner (May 17 vs May 31).

= Features =

* Sorts front-end event listings by end date
* Compatible with TEC 6.x (uses the `tec_occurrences` table)
* Lightweight — hooks into `posts_clauses` filter
* Customizable sort order via filter

= Filter: Change Sort Direction =

By default, events ending soonest appear first (ASC). To reverse:

`add_filter( 'tec_sort_by_end_date_order', function() {
    return 'DESC';
});`

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate through the Plugins menu
3. Events will now sort by end date on the front end

== Frequently Asked Questions ==

= Does this affect the admin area? =

No, only front-end event queries are modified.

= Does this work with recurring events? =

Yes, each occurrence is sorted by its individual end date.

= What TEC versions are supported? =

TEC 6.x and later (requires the `tec_occurrences` table).

== Changelog ==

= 1.1.0 =
* Updated for TEC 6.x compatibility
* Uses `tec_occurrences` table instead of postmeta
* Detects TEC queries by post_type instead of deprecated `tribe_is_event`

= 1.0.0 =
* Initial release (TEC 5.x)

== Upgrade Notice ==

= 1.1.0 =
Required update for The Events Calendar 6.x compatibility.
