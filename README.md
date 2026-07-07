![](https://heatbadger.now.sh/github/readme/contributte/latte/)

<p align=center>
  <a href="https://github.com/contributte/latte/actions"><img src="https://badgen.net/github/checks/contributte/latte/master?cache=300"></a>
  <a href="https://codecov.io/gh/contributte/latte"><img src="https://badgen.net/codecov/c/github/contributte/latte"></a>
  <a href="https://packagist.org/packages/contributte/latte"><img src="https://badgen.net/packagist/dm/contributte/latte"></a>
  <a href="https://packagist.org/packages/contributte/latte"><img src="https://badgen.net/packagist/v/contributte/latte"></a>
</p>
<p align=center>
  <a href="https://packagist.org/packages/contributte/latte"><img src="https://badgen.net/packagist/php/contributte/latte"></a>
  <a href="https://github.com/contributte/latte"><img src="https://badgen.net/github/license/contributte/latte"></a>
  <a href="https://bit.ly/ctteg"><img src="https://badgen.net/badge/support/gitter/cyan"></a>
  <a href="https://bit.ly/cttfo"><img src="https://badgen.net/badge/support/forum/yellow"></a>
  <a href="https://contributte.org/partners.html"><img src="https://badgen.net/badge/sponsor/donations/F96854"></a>
</p>

<p align=center>
Website 🚀 <a href="https://contributte.org">contributte.org</a> | Contact 👨🏻‍💻 <a href="https://f3l1x.io">f3l1x.io</a> | Twitter 🐦 <a href="https://twitter.com/contributte">@contributte</a>
</p>

Extra contribution to [`nette/latte`](https://github.com/nette/latte) with Latte extensions, filters, formatters and helper utilities.

## Versions

| State       | Version | Branch   | Nette | PHP     |
|-------------|---------|----------|-------|---------|
| dev         | `^0.7`  | `master` | 3.2+  | `>=8.2` |
| stable      | `^0.6`  | `master` | 3.2+  | `>=8.1` |

## Installation

To install latest version of `contributte/latte` use [Composer](https://getcomposer.org).

```bash
composer require contributte/latte
```

## Replacus

Simple string placeholder replacer using Latte templating engine. Replaces placeholders like `{$variable}` with provided values.

### Basic replacement

```php
use Contributte\Latte\Replacus\Replacus;

$replacus = Replacus::create();

// Simple variable replacement
$result = $replacus->replace('Hello {$name}!', ['name' => 'World']);
// Result: "Hello World!"

// URL with placeholder
$url = $replacus->replace('https://{$domain}/path', ['domain' => 'contributte.org']);
// Result: "https://contributte.org/path"

// Multiple variables
$result = $replacus->replace('User {$name} has {$count} messages', [
    'name' => 'John',
    'count' => 5,
]);
// Result: "User John has 5 messages"

// Array access
$result = $replacus->replace('{$items[0]} and {$items[1]}', [
    'items' => ['first', 'second'],
]);
// Result: "first and second"
```

### Custom Filters

You can add custom Latte filters:

```php
$replacus = Replacus::create()
    ->addFilter('upper', fn($s) => strtoupper($s))
    ->addFilter('trim', fn($s) => trim($s));

$result = $replacus->replace('{$text|trim|upper}', ['text' => '  hello  ']);
// Result: "HELLO"
```

### Custom Latte Engine

For advanced usage, you can provide your own configured Latte engine:

```php
use Latte\Engine;
use Latte\Loaders\StringLoader;

$latte = new Engine();
$latte->setLoader(new StringLoader());
// ... configure as needed

$replacus = new Replacus($latte);
```

## Versions Extension

This extension adds 3 macros: `{rev}`, `{build}`, `{v}`.

### Version extension registration

```neon
extensions:
	version: Contributte\Latte\DI\VersionExtension
```

### Version values

```neon
version:
	rev: e3203c85a9b84ee866132f371ba0b238b6a5b245
	build: 2016
	v: 2.12
```

Alternatively, you can use the `generated` property. It generates rev/build/v for each container compilation, i.e. each time in the debug mode and once in the production mode. Very useful.

```neon
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

## CDN Extension

This extension provides CDN support with `{cdn}` macro and `|cdn` filter for managing asset URLs.

### CDN extension registration

```neon
extensions:
	cdn: Contributte\Latte\DI\CdnExtension
```

### CDN configuration

```neon
cdn:
	url: https://cdn.example.com
	cacheBusting: time # or false
```

### CDN usage

```latte
{* Macro syntax *}
<link rel="stylesheet" href="{cdn 'assets/style.css'}">

{* Filter syntax *}
<script src="{='assets/dist/app.js'|cdn}"></script>
```

**Development (empty url):**
```
/assets/style.css?time=123456789
```

**Production (with CDN url):**
```
https://cdn.example.com/assets/style.css?time=123456789
```

## Parsedown Extension

This extension provides markdown parsing support via the `|parsedown` filter using [ParsedownExtra](https://github.com/erusev/parsedown-extra).

### Requirements

The `erusev/parsedown-extra` package is an optional dependency. Install it first:

```bash
composer require erusev/parsedown-extra
```

### Parsedown extension registration

```neon
extensions:
	parsedown: Contributte\Latte\DI\ParsedownExtension
```

### Parsedown configuration

```neon
parsedown:
	filter: parsedown # default filter name, can be changed to e.g. "markdown"
```

### Parsedown usage

```latte
{* Filter syntax *}
{$markdownContent|parsedown}

{* Block syntax *}
{block|parsedown}
# Hello World

This is **markdown** content.
{/block}
```

### Parsedown adapter usage

You can use the `ParsedownExtraAdapter` directly with callbacks for custom processing:

```php
use Contributte\Latte\Filters\ParsedownExtraAdapter;

$adapter = $container->getByType(ParsedownExtraAdapter::class);
$adapter->onProcess[] = function (string $text, ParsedownExtraAdapter $adapter): void {
	// Custom processing before markdown parsing
};
```

## Filters Extension

Install filters by single extension and simple `FiltersProvider` implementation.

### Filters extension registration

```neon
extensions:
	filters: Contributte\Latte\DI\FiltersExtension
```

### Filters provider usage

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

```neon
services:
	- MyFilters
```

That's all.

## Runtime Filters

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

```neon
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

```neon
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

## Development

See [how to contribute](https://contributte.org) to this package. This package is currently maintained by these authors.

<a href="https://github.com/f3l1x">
    <img width="80" height="80" src="https://avatars2.githubusercontent.com/u/538058?v=3&s=80">
</a>

-----

Consider to [support](https://contributte.org/partners) **contributte** development team.
Also thank you for using this package.
