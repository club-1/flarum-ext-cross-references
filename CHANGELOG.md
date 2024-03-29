# Changelog

## [unreleased]

### Removed

- Remove "retrofit links in frontend" feature's code and related setting. ([#42])

[#42]: https://github.com/club-1/flarum-ext-cross-references/issues/42

## [v1.1.1] - 2023-05-10

### Fixed

- Fix formatting of the "retrofit links in frontend" setting and reword it
  to precise that it is deprecated.

## [v1.1.0] - 2023-05-06

### Added

- Emit a `DiscussionReferenced` event each time a discussion is mentionned
  from another for the first time.
- Add foreign key constraints to the table schema, using the "cascade"
  delete policy. This enforces consistency in the database and increases
  the performances of the relation queries.
  The migration deletes the invalid rows before adding the constraints to
  prevent failures if previously referenced discussions have been deleted.
- Test more PHP versions.
- Recommend `club-1-chore-commands` extension to reparse old messages.
- Make `false` the default value for "retrofit links in frontend" setting.

### Deprecated

- The "retrofit links in frontend" feature will be removed in `v1.2.0`.
  Consider using the `chore:reparse` command of the [club-1/flarum-ext-chore-commands]
  extension instead.

[club-1/flarum-ext-chore-commands]: https://github.com/club-1/flarum-ext-chore-commands

## [v1.0.5] - 2023-04-06

### Fixed

- Fix links with utf8 slugs are not matched in PHP. ([#41])

[#41]: https://github.com/club-1/flarum-ext-cross-references/issues/41

## [v1.0.4] - 2023-02-22

### Fixed

- Fix duplicated onclick router events attached to discussion links.
  This prevents from messing up the browser history. (thanks to [@rob006],
  [#39])

[#39]: https://github.com/club-1/flarum-ext-cross-references/issues/39

## [v1.0.3] - 2023-02-20

### Added

- Add an option to disable the retrofit of old messages' discussion links
  in the frontend. ([#38])

### Fixed

- Fix links incorrectly displayed as `[unknown discussion]` when request or
  actor is null, by fallbacking to parsing and rendering discussion links
  as viewed by guests. (thanks to [@rob006] in [#32])
- Fix links incorrectly displayed as `[unknown discussion]` in some cases
  by improving the detection of permissions to view referenced discussions
  in both rendering and parsing. (thanks to [@rob006] in [#32])

### Changed

- Increase PHPStan level from 5 to 7 and add some return types along the way.
- Log actor/request is null error and stack trace only when Flarum is in
  debug mode. (thanks to [@rob006] in [#32])
- Do not replace unknown discussion links of CommentPosts by unclickable
  placeholders. We keep showing them the way it was written. ([#35], [#36])

[#32]: https://github.com/club-1/flarum-ext-cross-references/pull/32
[#35]: https://github.com/club-1/flarum-ext-cross-references/issues/35
[#36]: https://github.com/club-1/flarum-ext-cross-references/pull/36
[#38]: https://github.com/club-1/flarum-ext-cross-references/issues/38

## [v1.0.2] - 2023-02-14

### Fixed

- Fallback gracefully to invalidating tag parsing if actor is null.
- Detect again discussion URL in manually created links, and create a
  cross-reference in target discussion.
  This has been broken since [v0.5.0] when TextFormatter was added. ([#27])
- Remove space after source link's title when show ID is disabled.

[#27]: https://github.com/club-1/flarum-ext-cross-references/issues/27

### Changed

- Add this CHANGELOG.md file.
- Add and setup PHPStan for static analysis of PHP code. ([#25])
- Use different flags for non-interactive shells in Makefile.
- Add script to bump CHANGELOG.md on release.
- Add tests for TextFormatter templates rendering. ([#26])

[#25]: https://github.com/club-1/flarum-ext-cross-references/pull/25
[#26]: https://github.com/club-1/flarum-ext-cross-references/issues/26

## [v1.0.1] - 2023-02-12

### Fixed

- Fallback gracefully to unknown discussion rendering if request is null.

### Changed

- New translation reserved for merged DiscussionReferencedPost.

## [v1.0.0] - 2023-02-09

First stable release.

[@rob006]: https://github.com/rob006

[unreleased]: https://github.com/club-1/flarum-ext-cross-references/compare/v1.1.1...HEAD
[v1.1.1]: https://github.com/club-1/flarum-ext-cross-references/releases/tag/v1.1.1
[v1.1.0]: https://github.com/club-1/flarum-ext-cross-references/releases/tag/v1.1.0
[v1.0.5]: https://github.com/club-1/flarum-ext-cross-references/releases/tag/v1.0.5
[v1.0.4]: https://github.com/club-1/flarum-ext-cross-references/releases/tag/v1.0.4
[v1.0.3]: https://github.com/club-1/flarum-ext-cross-references/releases/tag/v1.0.3
[v1.0.2]: https://github.com/club-1/flarum-ext-cross-references/releases/tag/v1.0.2
[v1.0.1]: https://github.com/club-1/flarum-ext-cross-references/releases/tag/v1.0.1
[v1.0.0]: https://github.com/club-1/flarum-ext-cross-references/releases/tag/v1.0.0
[v0.5.0]: https://github.com/club-1/flarum-ext-cross-references/releases/tag/v0.5.0
