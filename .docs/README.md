# Contributte Latte

Extra contribution to [`nette/latte`](https://github.com/nette/latte).

## Content

- [Setup](#setup)
- [VersionExtension - revision macros for assets](#versions-extension)
- [FiltersExtension - install filters easily](#filters-extension)
- [RuntimeFilters - collection of prepared filters](#runtimefilters)
- [Formatters - collection of prepared formatters](#formatters)

## Setup

```bash
composer require contributte/latte
```

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

Alternatively, you can use the `generated` property. It generates rev/build/v for each container compilation, i.e. each time in the debug mode and once in the production mode. Very useful.

```yaml
version:
    generated: %debugMode%
    # rev: auto-generated
    # build: auto-generated
    # v: auto-generated
```

```latte
<link rel="stylesheet" href="{$basePath}/assets/theme.css?v={rev}">
<link rel="stylesheet" href="{$basePath}/assets/theme.css?v={build}">
<link rel="stylesheet" href="{$basePath}/assets/theme.css?v={v}">
```

## Filters Extension

Install filters by single extension and simple `FiltersProvider` implementation.

### Installation

```yaml
extensions:
    filters: Contributte\Latte\DI\FiltersExtension
```

### Usage

First of all, you have to define your own filters provider. It's `key => value`, that means `name => callback` array.

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

After that, add you filters provider as a service to your config (neon) file.

```yaml
services:
    - MyFilters
```

That's all.

## RuntimeFilters

### `Filectime`

```latte
<link rel="stylesheet" href="{=$basePath/assets/theme.css|filectime}">
```

```html
<link rel="stylesheet" href=/assets/theme.css?v=123456789">
```

### `Email`

```latte
{var $email = "my@email.net"}

{$email|email:"javascript"}
{$email|email:"javascript_charcode"}
{$email|email:"hex"|noescape}
{$email|email:"javascript":"link to my email here"}
{$email|email:"drupal"}
{$email|email:"texy"}
```

```html
This is my email: <a href="mailto: my[at]email.org">my[at]email.org</a>
```

#### Supported encoding methods

* javascript
* javascript_charcode
* hex
* drupal
* texy

#### Nette DI setup

```yaml
services:
    nette.latteFactory:
        setup:
            - addFilter('email', ['Contributte\Latte\Filters\EmailFilter', 'filter'])
```

### Gravatar

Create link to [gravatar image](https://cs.gravatar.com/site/implement/images/)

```latte
<img src="{lorem@ipsum.com|gravatar}"/>
<img src="https://www.gravatar.com/avatar/067398c3f23785981cd8672e21643405.jpg?default=retro&size=80"/>
```

```html
<img src="{lorem@ipsum.com|gravatar:[size=>48, style=>robohash, format=>png]}"/>
<img src="https://www.gravatar.com/avatar/067398c3f23785981cd8672e21643405.png?default=robohash&size=48"/>
```

## Formatters

### `NumberFormatter`

Number formatter is simple wrapper class over the `number_format` function.

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
