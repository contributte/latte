<?php declare(strict_types = 1);

use Contributte\Latte\Filters\FilectimeFilter;
use Contributte\Tester\Toolkit;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

Toolkit::test(function (): void {
	$ctime = FilectimeFilter::filectime(__DIR__ . '/../../Fixtures/files/foobar.txt');
	Assert::match('%d%', $ctime);

	$ctime2 = FilectimeFilter::filectime(__DIR__ . '/../../Fixtures/files/foobar.txt', 'd-m-Y');
	Assert::match('%d%-%d%-%d%', $ctime2);
});

Toolkit::test(function (): void {
	$ctime = FilectimeFilter::filter(__DIR__ . '/../../Fixtures/files/foobar.txt');
	Assert::match('%a%/foobar.txt?v=%d%', $ctime);

	$ctime = FilectimeFilter::filter(__DIR__ . '/../../Fixtures/files/foobar.txt', 'd-m-Y');
	Assert::match('%a%/foobar.txt?v=%d%-%d%-%d%', $ctime);
});
