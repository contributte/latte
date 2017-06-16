<?php

/**
 * @Test Filters\FilectimeFilter
 */

use Contributte\Latte\Filters\FilectimeFilter;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

test(function () {
	$ctime = FilectimeFilter::filectime(__DIR__ . '/../../fixtures/files/foobar.txt');
	Assert::equal('1497610291', $ctime);

	$ctime2 = FilectimeFilter::filectime(__DIR__ . '/../../fixtures/files/foobar.txt', 'd-m-Y');
	Assert::equal('16-06-2017', $ctime2);
});

test(function () {
	$ctime = FilectimeFilter::filter(__DIR__ . '/../../fixtures/files/foobar.txt');
	Assert::equal(__DIR__ . '/../../fixtures/files/foobar.txt?v=1497610291', $ctime);

	$ctime = FilectimeFilter::filter(__DIR__ . '/../../fixtures/files/foobar.txt', 'd-m-Y');
	Assert::equal(__DIR__ . '/../../fixtures/files/foobar.txt?v=16-06-2017', $ctime);
});
