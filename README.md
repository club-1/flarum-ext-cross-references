# CLUB1 Cross-references

![License](https://img.shields.io/badge/license-AGPL--3.0--or--later-blue) [![Latest Stable Version](https://img.shields.io/packagist/v/club-1/flarum-ext-cross-references.svg)](https://packagist.org/packages/club-1/flarum-ext-cross-references) [![Total Downloads](https://img.shields.io/packagist/dt/club-1/flarum-ext-cross-references.svg)](https://packagist.org/packages/club-1/flarum-ext-cross-references) [![Coverage](https://img.shields.io/codecov/c/gh/club-1/flarum-ext-cross-references/main?token=FZFGE1M3GW)](https://codecov.io/gh/club-1/flarum-ext-cross-references) [![Build Status](https://img.shields.io/github/actions/workflow/status/club-1/flarum-ext-cross-references/check.yml?branch=main)](https://github.com/club-1/flarum-ext-cross-references/actions/workflows/check.yml)

A [Flarum](http://flarum.org) extension. Add cross reference links when a discussion is mentioned from another one.

![cross reference screenshot](https://static.club1.fr/nicolas/projects/flarum-ext-cross-references/banner.png)

## Features

This extension is mainly inspired by GitHub cross linking features.
It adds similar features to Flarum forums.

- EventPost in target discussion:
  - [x] is created when a post referencing it is saved
  - [x] links back to the source and displays its title
  - [x] shows the ID of the source (option)
  - [ ] shows the primary tags of the source (option)
  - [ ] shows the secondary tags of the source (option)
- Links in source discussion:
  - [x] are changed to show the title of target discussion when the text is the same as the href
  - [x] have `(comment)` indication if it points to a specific comment
  - [x] load the target discussion faster as they use the _FrontEnd Router_
  - [x] are shown in the preview the way they will be seen once posted
  - [x] are automatically created from `#<id>` text (e.g. `#42`) (option)
  - [ ] are auto-completed with a selection box when `#` is entered
  - [x] show the ID of the target (option)
  - [ ] show the primary tags of the target (option)
  - [ ] show the secondary tags of the target (option)
- Discussion list:
  - [x] shows the ID of the discussions (option)
- HeaderRow
  - [x] shows the ID of the discussion (option)

## Known issues

### Discussion links sometimes show plain links instead of the discussion title in rendered post, but are shown as expected in preview.

This is because the renderer is called with `$request = null`.
It is probably due to another extension not setting this parameter when calling
[`Formatter->render()`](https://api.docs.flarum.org/php/v1.6.0/flarum/formatter/formatter#method_render)
or [`CommentPost->formatContent()`](https://api.docs.flarum.org/php/v1.6.0/flarum/post/commentpost#method_formatContent).

Enable [Flarum's debug mode](https://docs.flarum.org/troubleshoot/#step-0-activate-debug-mode)
then check [Flarum's log](https://docs.flarum.org/troubleshoot/#step-3-collect-information)
to find the source of the issue.

Here is an example of how this issue should be fixed in said extensions:
[the-turk/flarum-diff#35](https://github.com/the-turk/flarum-diff/pull/35)

## Installation

Install with composer:

```sh
composer require club-1/flarum-ext-cross-references
```

### Recommandation

This extension alone does not apply the formatting changes to previously posted comments. I you want to reparse all the comments posts of the database it is recommended to install and enable the [`club-1/flarum-ext-chore-commands`](https://github.com/club-1/flarum-ext-chore-commands) extension and use its `chore:reparse` command.

## Updating

```sh
composer update club-1/flarum-ext-cross-references
php flarum migrate
php flarum cache:clear
```

## Links

- [Packagist](https://packagist.org/packages/club-1/flarum-ext-cross-references)
- [GitHub](https://github.com/club-1/flarum-ext-cross-references)
- [Discuss](https://discuss.flarum.org/d/32100)
