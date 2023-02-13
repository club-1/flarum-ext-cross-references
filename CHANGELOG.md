# Changelog

## [unreleased]

### Fixed

- Fallback gracefully to invalidating tag parsing if actor is null.
- Detect again discussion URL in manually created links, and create a
  cross-reference in target discussion.
  This has been broken since v0.5.0 when TextFormatter was added.

### Changed

- Add this CHANGELOG.md file.
- Add and setup PHPStan for static analysis of PHP code.
- Use different flags for non-interactive shells in Makefile.
- Add script to bump CHANGELOG.md on release.

## [v1.0.1] - 2023-02-12

### Fixed

- Fallback gracefully to unknown discussion rendering if request is null.

### Changed

- New translation reserved for merged DiscussionReferencedPost.

## [v1.0.0] - 2023-02-09

First stable release.

[unreleased]: https://github.com/club-1/flarum-ext-cross-references/compare/v1.0.1...HEAD
[v1.0.1]: https://github.com/club-1/flarum-ext-cross-references/releases/tag/v1.0.1
[v1.0.0]: https://github.com/club-1/flarum-ext-cross-references/releases/tag/v1.0.0
