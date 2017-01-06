# Contributte > Latte

:sparkles: Extra contribution to [`nette/latte`](https://github.com/nette/latte).

-----

[![Build Status](https://img.shields.io/travis/contributte/http.svg?style=flat-square)](https://travis-ci.org/contributte/http)
[![Code coverage](https://img.shields.io/coveralls/contributte/http.svg?style=flat-square)](https://coveralls.io/r/contributte/http)
[![HHVM Status](https://img.shields.io/hhvm/contributte/http.svg?style=flat-square)](http://hhvm.h4cc.de/package/contributte/http)
[![Licence](https://img.shields.io/packagist/l/contributte/http.svg?style=flat-square)](https://packagist.org/packages/contributte/http)

[![Downloads this Month](https://img.shields.io/packagist/dm/contributte/http.svg?style=flat-square)](https://packagist.org/packages/contributte/http)
[![Downloads total](https://img.shields.io/packagist/dt/contributte/http.svg?style=flat-square)](https://packagist.org/packages/contributte/http)
[![Latest stable](https://img.shields.io/packagist/v/contributte/http.svg?style=flat-square)](https://packagist.org/packages/contributte/http)
[![Latest unstable](https://img.shields.io/packagist/vpre/contributte/http.svg?style=flat-square)](https://packagist.org/packages/contributte/http)

## Discussion / Help

[![Join the chat](https://img.shields.io/gitter/room/contributte/contributte.svg?style=flat-square)](http://bit.ly/ctteg)

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
