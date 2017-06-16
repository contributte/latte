# Latte

## Content

- [VersionExtension - revision macros for assets](#versions-extension)
- [FiltersExtension - install filters easily](#filers-extension)
- [TemplateFactory - events](#templatefactory)
- [RuntimeFilters - collection of prepared filters](#runtime-filters)

## Version(s) Extension

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

## Filters(s) Extension

Install filters by single extension and simple `FiltersProvider` implementation.

### Install

```yaml
extensions:
    filters: Contributte\Latte\DI\FiltersExtension
```

### Usage

First of all, you have to define your own filters provider. It's key => value, I mean name => callback array.

```php
use Contributte\Latte\Filters\FiltersProvider;

final class MyFilters implements FiltersProvider
{

	/**
	 * @return array
	 */
	public function getFilters()
	{
		return [
			'say' => function ($hi) {
				return sprintf('Hi %s!', $hi);
			},
		];
	}

}
```

After that, add you filters provider as service to your config (neon) file.

```yaml
services:
    - MyFilters
```

That's all.

## TemplateFactory

Our implementation adds `$onCreate` nette-based event.

So you don't need to extends and override stuff, but do it via nette-based `$onCreate` event.

```php
$templateFactory->onCreate[] = function (Template $template, Control $control = NULL) {
    // do magic..
};

```

Easy, right? :gift:

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
