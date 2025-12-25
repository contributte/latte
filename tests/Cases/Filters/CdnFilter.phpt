<?php declare(strict_types = 1);

use Contributte\Latte\Filters\CdnFilter;
use Contributte\Tester\Toolkit;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

// Test CDN filter with base URL
Toolkit::test(function (): void {
	$url = CdnFilter::filter('assets/style.css', [
		'url' => 'https://cdn.example.com',
	]);
	Assert::equal('https://cdn.example.com/assets/style.css', $url);

	$url = CdnFilter::filter('js/app.js', [
		'url' => 'https://cdn.example.com',
	]);
	Assert::equal('https://cdn.example.com/js/app.js', $url);
});

// Test CDN filter with base URL and cacheBusting
Toolkit::test(function (): void {
	$url = CdnFilter::filter('assets/style.css', [
		'url' => 'https://cdn.example.com',
		'cacheBusting' => 'time',
	]);
	Assert::true(str_starts_with($url, 'https://cdn.example.com/assets/style.css?time='));
	Assert::match('~^https://cdn\.example\.com/assets/style\.css\?time=\d+$~', $url);
});

// Test CDN filter without base URL (localhost)
Toolkit::test(function (): void {
	$url = CdnFilter::filter('assets/style.css', [
		'url' => '',
	]);
	Assert::equal('/assets/style.css', $url);

	$url = CdnFilter::filter('assets/style.css');
	Assert::equal('/assets/style.css', $url);
});

// Test CDN filter without base URL with cacheBusting
Toolkit::test(function (): void {
	$url = CdnFilter::filter('assets/style.css', [
		'url' => '',
		'cacheBusting' => 'time',
	]);
	Assert::true(str_starts_with($url, '/assets/style.css?time='));
	Assert::match('~^/assets/style\.css\?time=\d+$~', $url);
});

// Test CDN filter with trailing slash handling
Toolkit::test(function (): void {
	$url = CdnFilter::filter('assets/style.css', [
		'url' => 'https://cdn.example.com/',
	]);
	Assert::equal('https://cdn.example.com/assets/style.css', $url);

	$url = CdnFilter::filter('/assets/style.css', [
		'url' => 'https://cdn.example.com/',
	]);
	Assert::equal('https://cdn.example.com/assets/style.css', $url);

	$url = CdnFilter::filter('/assets/style.css', [
		'url' => 'https://cdn.example.com',
	]);
	Assert::equal('https://cdn.example.com/assets/style.css', $url);
});

// Test CDN filter with query string already present
Toolkit::test(function (): void {
	$url = CdnFilter::filter('assets/style.css?v=1.0', [
		'url' => 'https://cdn.example.com',
		'cacheBusting' => 'time',
	]);
	Assert::match('~^https://cdn\.example\.com/assets/style\.css\?v=1\.0&time=\d+$~', $url);
});
