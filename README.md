# TEC – Sort Multi-Day Events by End Date

Sort [The Events Calendar](https://theeventscalendar.com/) events by end date instead of start date.

## Description

Makes The Events Calendar sort events by end date (ascending) instead of start date. Useful when you have multi-day events and want the one ending soonest to appear first.

**Example:** You have two events:
- "Hele mai" — runs May 1–31
- "midten av mai" — runs May 4–17

By default, TEC sorts by start date, showing "Hele mai" first. With this plugin, "midten av mai" appears first because it ends sooner (May 17 vs May 31).

## Features

- Sorts front-end event listings by end date
- Compatible with TEC 6.x (uses the `tec_occurrences` table)
- Lightweight — hooks into `posts_clauses` filter
- Customizable sort order via filter

## Installation

1. Download [`tec-sort-by-end-date.zip`](https://github.com/soderlind/tec-sort-by-end-date/releases/latest/download/tec-sort-by-end-date.zip)
2. Upload via  `Plugins → Add New → Upload Plugin`
3. Activate via `WordPress Admin → Plugins`

## Customization

### Change Sort Direction

By default, events ending soonest appear first (ASC). To reverse:

```php
add_filter( 'tec_sort_by_end_date_order', function() {
    return 'DESC';
});
```

## FAQ

### Does this affect the admin area?

No, only front-end event queries are modified.

### Does this work with recurring events?

Yes, each occurrence is sorted by its individual end date.

### What TEC versions are supported?

TEC 6.x and later (requires the `tec_occurrences` table).

## Requirements

- WordPress 6.0+
- PHP 7.4+
- The Events Calendar 6.x+

## License

GPL-2.0+
