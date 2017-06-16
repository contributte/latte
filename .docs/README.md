# Latte

## Content

- [VersionExtension - revision macros for assets](#versions)

### Version(s)

This extension adds 3 macros: `{rev}`, `{build}`, `{v}`.

### Install

```yaml
extensions:
    version: Contributte\Latte\DI\VersionExtension
```

### Configuration

```yaml
version:
    rev: e3203c85a9b84ee866132f371ba0b238b6a5b245
    build: 2016
    v: 2.12
```

In debug mode you can use `debug` property to generated rev/build/v for each container compilation.

```yaml
version:
    debug: %debugMode%
    # rev: auto-generated
    # build: auto-generated
    # v: auto-generated
```

```html
<link rel="stylesheet" href="{$basePath}/assets/theme.css?v={rev}">
<link rel="stylesheet" href="{$basePath}/assets/theme.css?v={build}">
<link rel="stylesheet" href="{$basePath}/assets/theme.css?v={v}">
```
