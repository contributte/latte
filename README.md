# Contributte > Latte

:sparkles: Extra contribution to [`nette/latte`](https://github.com/nette/latte).

-----

[![Build Status](https://img.shields.io/travis/contributte/latte.svg?style=flat-square)](https://travis-ci.org/contributte/latte)
[![Code coverage](https://img.shields.io/coveralls/contributte/latte.svg?style=flat-square)](https://coveralls.io/r/contributte/latte)
[![Downloads this Month](https://img.shields.io/packagist/dm/contributte/latte.svg?style=flat-square)](https://packagist.org/packages/contributte/latte)
[![Downloads total](https://img.shields.io/packagist/dt/contributte/latte.svg?style=flat-square)](https://packagist.org/packages/contributte/latte)
[![Latest stable](https://img.shields.io/packagist/v/contributte/latte.svg?style=flat-square)](https://packagist.org/packages/contributte/latte)
[![HHVM Status](https://img.shields.io/hhvm/contributte/latte.svg?style=flat-square)](http://hhvm.h4cc.de/package/contributte/latte)

## Discussion / Help

[![Join the chat](https://img.shields.io/gitter/room/contributte/contributte.svg?style=flat-square)](https://gitter.im/<GITTER>?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

## Install

```
composer require contributte/latte
```

## Usage

### VersionExtension

This extension adds 3 macros: `{rev}`, `{build}`, `{v}`.

```yaml
extensions:
    version: Contributte\Latte\DI\VersionExtension
```

```yaml
version:
    rev: e3203c85a9b84ee866132f371ba0b238b6a5b245
    build: 2016
    v: 2.12
```

```html
<link rel="stylesheet" href="{$basePath}/assets/theme.css?v={rev}">
<link rel="stylesheet" href="{$basePath}/assets/theme.css?v={build}">
<link rel="stylesheet" href="{$basePath}/assets/theme.css?v={v}">
```

-------

Thank you for testing, reporting and contributing.
