# Changelog

## [unreleased]

### Fixed

- Fix links incorrectly displayed as [unknown discussion] when request or
  actor is null, by fallbacking to parsing and rendering discussion links
  as viewed by guests.
- Fix links incorrectly displayed as [unknown discussion] in some cases
  by improving the detection of permissions to view referenced discussions
  in both rendering and parsing.

### Changed

- Increase PHPStan level from 5 to 7 and add some return types along the way.
- Log actor/request is null error and stack trace only when Flarum is in
  debug mode.

## [v1.0.2] - 2023-02-14

### Fixed

- Fallback gracefully to invalidating tag parsing if actor is null.
- Detect again discussion URL in manually created links, and create a
  cross-reference in target discussion.
  This has been broken since v0.5.0 when TextFormatter was added. (#27)
- Remove space after source link's title when show ID is disabled.

### Changed

- Add this CHANGELOG.md file.
- Add and setup PHPStan for static analysis of PHP code.
- Use different flags for non-interactive shells in Makefile.
- Add script to bump CHANGELOG.md on release.
- Add tests for TextFormatter templates rendering. (#26)

## [v1.0.1] - 2023-02-12

### Fixed

- Fallback gracefully to unknown discussion rendering if request is null.

### Changed

- New translation reserved for merged DiscussionReferencedPost.

## [v1.0.0] - 2023-02-09

First stable release.

[unreleased]: https://github.com/club-1/flarum-ext-cross-references/compare/v1.0.2...HEAD
[v1.0.2]: https://github.com/club-1/flarum-ext-cross-references/releases/tag/v1.0.2
[v1.0.1]: https://github.com/club-1/flarum-ext-cross-references/releases/tag/v1.0.1
[v1.0.0]: https://github.com/club-1/flarum-ext-cross-references/releases/tag/v1.0.0
