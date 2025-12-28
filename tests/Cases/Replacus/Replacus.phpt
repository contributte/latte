<?php declare(strict_types = 1);

use Contributte\Latte\Replacus\Replacus;
use Contributte\Tester\Toolkit;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

// Test simple variable replacement
Toolkit::test(function (): void {
	$replacus = Replacus::create();
	$result = $replacus->replace('{$foo}', ['foo' => 'bar']);
	Assert::equal('bar', $result);
});

// Test integer replacement
Toolkit::test(function (): void {
	$replacus = Replacus::create();
	$result = $replacus->replace('{$num}', ['num' => 1]);
	Assert::equal('1', $result);
});

// Test array index replacement
Toolkit::test(function (): void {
	$replacus = Replacus::create();
	$result = $replacus->replace('{$foo[0]}{$foo[1]}', ['foo' => ['a', 'b']]);
	Assert::equal('ab', $result);
});

// Test URL replacement (from original docs)
Toolkit::test(function (): void {
	$replacus = Replacus::create();
	$result = $replacus->replace('https://{$domain}', ['domain' => 'contributte.org']);
	Assert::equal('https://contributte.org', $result);
});

// Test multiple variables
Toolkit::test(function (): void {
	$replacus = Replacus::create();
	$result = $replacus->replace('Hello {$name}, you have {$count} messages', [
		'name' => 'John',
		'count' => 5,
	]);
	Assert::equal('Hello John, you have 5 messages', $result);
});

// Test with custom filter
Toolkit::test(function (): void {
	$replacus = Replacus::create();
	$replacus->addFilter('upper', fn($s) => strtoupper($s));
	$result = $replacus->replace('{$text|upper}', ['text' => 'hello']);
	Assert::equal('HELLO', $result);
});

// Test constructor with custom Latte engine
Toolkit::test(function (): void {
	$latte = new Latte\Engine();
	$latte->setLoader(new Latte\Loaders\StringLoader());

	$replacus = new Replacus($latte);
	$result = $replacus->replace('{$foo}', ['foo' => 'custom']);
	Assert::equal('custom', $result);
});

// Test getLatte method
Toolkit::test(function (): void {
	$replacus = Replacus::create();
	Assert::type(Latte\Engine::class, $replacus->getLatte());
});

// Test empty args
Toolkit::test(function (): void {
	$replacus = Replacus::create();
	$result = $replacus->replace('static text');
	Assert::equal('static text', $result);
});

// Test fluent interface for addFilter
Toolkit::test(function (): void {
	$replacus = Replacus::create()
		->addFilter('lower', fn($s) => strtolower($s))
		->addFilter('trim', fn($s) => trim($s));

	$result = $replacus->replace('{$text|trim|lower}', ['text' => '  HELLO  ']);
	Assert::equal('hello', $result);
});
