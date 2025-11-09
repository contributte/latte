<?php declare(strict_types = 1);

use Contributte\Latte\Extensions\CdnExtension;
use Contributte\Tester\Environment;
use Contributte\Tester\Toolkit;
use Latte\Engine;
use Latte\Loaders\StringLoader;
use Tester\Assert;
use Tester\FileMock;

require_once __DIR__ . '/../../bootstrap.php';

// Test CDN macro with base URL
Toolkit::test(function (): void {
	$latte = new Engine();
	$latte->setTempDirectory(Environment::getTestDir());
	$latte->setLoader(new StringLoader());
	$latte->addExtension(new CdnExtension([
		'url' => 'https://cdn.example.com',
	]));

	Assert::equal('https://cdn.example.com/assets/style.css', $latte->renderToString('{cdn "assets/style.css"}'));
	Assert::equal('https://cdn.example.com/js/app.js', $latte->renderToString('{cdn "js/app.js"}'));
});

// Test CDN macro with base URL and cacheBusting
Toolkit::test(function (): void {
	$latte = new Engine();
	$latte->setTempDirectory(Environment::getTestDir());
	$latte->setLoader(new StringLoader());
	$latte->addExtension(new CdnExtension([
		'url' => 'https://cdn.example.com',
		'cacheBusting' => 'time',
	]));

	$result = $latte->renderToString('{cdn "assets/style.css"}');
	Assert::true(str_starts_with($result, 'https://cdn.example.com/assets/style.css?time='));
});

// Test CDN macro without base URL (localhost)
Toolkit::test(function (): void {
	$latte = new Engine();
	$latte->setTempDirectory(Environment::getTestDir());
	$latte->setLoader(new StringLoader());
	$latte->addExtension(new CdnExtension([
		'url' => '',
	]));

	Assert::equal('/assets/style.css', $latte->renderToString('{cdn "assets/style.css"}'));
});

// Test CDN macro without base URL with cacheBusting
Toolkit::test(function (): void {
	$latte = new Engine();
	$latte->setTempDirectory(Environment::getTestDir());
	$latte->setLoader(new StringLoader());
	$latte->addExtension(new CdnExtension([
		'url' => '',
		'cacheBusting' => 'time',
	]));

	$result = $latte->renderToString('{cdn "assets/style.css"}');
	Assert::true(str_starts_with($result, '/assets/style.css?time='));
});

// Test CDN macro with FileMock
Toolkit::test(function (): void {
	$latte = new Engine();
	$latte->setTempDirectory(Environment::getTestDir());
	$latte->addExtension(new CdnExtension([
		'url' => 'https://cdn.example.com',
	]));

	Assert::equal('https://cdn.example.com/assets/style.css', $latte->renderToString(FileMock::create('{cdn "assets/style.css"}', 'latte')));
});

// Test CDN filter with base URL
Toolkit::test(function (): void {
	$latte = new Engine();
	$latte->setTempDirectory(Environment::getTestDir());
	$latte->setLoader(new StringLoader());
	$latte->addExtension(new CdnExtension([
		'url' => 'https://cdn.example.com',
	]));

	Assert::equal('https://cdn.example.com/assets/style.css', $latte->renderToString('{="assets/style.css"|cdn}'));
	Assert::equal('https://cdn.example.com/js/app.js', $latte->renderToString('{="js/app.js"|cdn}'));
});

// Test CDN filter with cacheBusting
Toolkit::test(function (): void {
	$latte = new Engine();
	$latte->setTempDirectory(Environment::getTestDir());
	$latte->setLoader(new StringLoader());
	$latte->addExtension(new CdnExtension([
		'url' => 'https://cdn.example.com',
		'cacheBusting' => 'time',
	]));

	$result = $latte->renderToString('{="assets/style.css"|cdn}');
	Assert::true(str_starts_with($result, 'https://cdn.example.com/assets/style.css?time='));
});

// Test CDN filter without base URL
Toolkit::test(function (): void {
	$latte = new Engine();
	$latte->setTempDirectory(Environment::getTestDir());
	$latte->setLoader(new StringLoader());
	$latte->addExtension(new CdnExtension([
		'url' => '',
	]));

	Assert::equal('/assets/style.css', $latte->renderToString('{="assets/style.css"|cdn}'));
});

// Test CDN macro with trailing slash handling
Toolkit::test(function (): void {
	$latte = new Engine();
	$latte->setTempDirectory(Environment::getTestDir());
	$latte->setLoader(new StringLoader());
	$latte->addExtension(new CdnExtension([
		'url' => 'https://cdn.example.com/',
	]));

	Assert::equal('https://cdn.example.com/assets/style.css', $latte->renderToString('{cdn "assets/style.css"}'));
	Assert::equal('https://cdn.example.com/assets/style.css', $latte->renderToString('{cdn "/assets/style.css"}'));
});
