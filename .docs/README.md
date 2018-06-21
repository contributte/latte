# Latte

## Content

- [VersionExtension - revision macros for assets](#versions-extension)
- [FiltersExtension - install filters easily](#filers-extension)
- [RuntimeFilters - collection of prepared filters](#runtimefilters)
- [Formatters - collection of prepared formatters](#formatters)

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

Or you can use property `generated`. It generates rev/build/v for each container compilation, so each time in debug mode and once in production mode. Very useful.

```yaml
version:
    generated: %debugMode%
    # rev: auto-generated
    # build: auto-generated
    # v: auto-generated
```

```html
<link rel="stylesheet" href="{$basePath}/assets/theme.css?v={rev}">
<link rel="stylesheet" href="{$basePath}/assets/theme.css?v={build}">
<link rel="stylesheet" href="{$basePath}/assets/theme.css?v={v}">
```

## Filters Extension

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
   	 * @return callable[]
     */
	public function getFilters(): array
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

## Formatters

### `NumberFormatter`

Number formatter is simple wrapping class over `number_format` method.

```yaml
services:
    formatter.money:
        # with defined prefix
        class: Contributte\Latte\Formatters\NumberFormatter('Kč')

    formatter.weight:
        # with defined prefix and suffix
        class: Contributte\Latte\Formatters\NumberFormatter('kg', '~')
        setup:
            - setThousands('')
            - setDecimals(0)

    nette.latteFactory:
        setup:
            # used as latte filter
            - addFilter(money, [@formatter.number, format])
            - addFilter(weight, [@formatter.weight, format])
```

Methods:

- `setDecimals(int $decimals)`
- `setPoint(string $separator)`
- `setThousands(string $separator)`
- `setZeros(bool $display)`
- `setSuffix(string $suffix)`
- `setPrefix(string $prefix)`
- `setString(bool $throw)`
- `setSpaces(bool $display)`
- `setCallback(callable $callback)`
