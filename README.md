# CLUB1 Cross-references

![License](https://img.shields.io/badge/license-AGPL--3.0--or--later-blue) [![Latest Stable Version](https://img.shields.io/packagist/v/club-1/flarum-ext-cross-references.svg)](https://packagist.org/packages/club-1/flarum-ext-cross-references) [![Total Downloads](https://img.shields.io/packagist/dt/club-1/flarum-ext-cross-references.svg)](https://packagist.org/packages/club-1/flarum-ext-cross-references)

A [Flarum](http://flarum.org) extension. Add cross reference links when a discussion is mentioned from another one.

![cross reference screenshot](https://static.club1.fr/nicolas/flarum-ext-cross-references.png)

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
  - [ ] are shown in the preview the way they will be seen once posted
  - [ ] are automatically created from `#<id>` text (e.g. `#42`)
  - [ ] are auto-completed with a selection box when `#` is entered
  - [x] show the ID of the target (option)
  - [ ] show the primary tags of the target (option)
  - [ ] show the secondary tags of the target (option)
- Discussion list:
  - [x] shows the ID of the discussions (option)
- HeaderRow
  - [x] shows the ID of the discussion (option)

## Installation

Install with composer:

```sh
composer require club-1/flarum-ext-cross-references:"*"
```

## Updating

```sh
composer update club-1/flarum-ext-cross-references:"*"
php flarum migrate
php flarum cache:clear
```

## Links

- [Packagist](https://packagist.org/packages/club-1/flarum-ext-cross-references)
- [GitHub](https://github.com/club-1/flarum-ext-cross-references)
- [Discuss](https://discuss.flarum.org/d/32100)
