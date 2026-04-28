# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.1] - 2026-04-28

### Added
- Add GitHub Updater

## [1.1.0] - 2026-04-28

### Changed
- Updated for The Events Calendar 6.x compatibility
- Now uses `tec_occurrences` table instead of postmeta (`EventEndDate` alias)
- Detects TEC queries by `post_type` instead of deprecated `$query->tribe_is_event`

### Fixed
- Plugin now works with TEC 6.x which uses a custom occurrences table

## [1.0.0] - 2026-04-28

### Added
- Initial release
- Sort events by end date on front-end queries
- Filter `tec_sort_by_end_date_order` to control sort direction (ASC/DESC)

## AI Attribution

Assisted-by: GitHub Copilot:claude-opus-4
