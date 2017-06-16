# Latte

## Content

- [VersionExtension - revision macros for assets](#versions)
- [RuntimeFilters - collection of prepared filters](#runtime-filters)

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

## RuntimeFilters

### `Filectime`

```html
<link rel="stylesheet" href="{=$basePath/assets/theme.css|filectime}">
```

```html
<link rel="stylesheet" href=/assets/theme.css?v=123456789">
```

### `Email`

```html
{var $email => 'my@email.cz'}

This is my email: {$email|email}.
This is my email: {="my@email.cz"|email}.
```

```html
This is my email: <a href="mailto: my[at]email.org">my[at]email.org</a>
```
