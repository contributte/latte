<?php

/**
 * Test: Formatters\NumberFormatter
 */

use Contributte\Latte\Exception\Logical\InvalidArgumentException;
use Contributte\Latte\Formatters\NumberFormatter;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

test(function () {
	$formatter = new NumberFormatter();
	Assert::same('10', $formatter->format(10));
});

test(function () {
	$formatter = new NumberFormatter();
	Assert::same('10,1', $formatter->format(10.1));
	Assert::same('10 000', $formatter->format(10000));
});

test(function () {
	$formatter = new NumberFormatter();
	Assert::same('10,1', $formatter->format(10.10));
	Assert::same('10,1', $formatter->format(10.10000));
});

test(function () {
	$formatter = new NumberFormatter();
	Assert::same('10,1', $formatter->format(' 10.10 '));
	Assert::same('10,1', $formatter->format('10.10 '));
	Assert::same('10,1', $formatter->format(' 10.10'));
});

test(function () {
	$suffix = 'cm';
	$formatter = new NumberFormatter($suffix);
	Assert::same('10,1 cm', $formatter->format('10.1'));
	Assert::same('10,1 cm', $formatter->format('10.1 '));
	Assert::same('10,1 cm', $formatter->format(' 10.1 '));

	$suffix = ' cm ';
	$formatter = new NumberFormatter($suffix);
	Assert::same('10,1  cm', $formatter->format('10.1'));
	Assert::same('10,1  cm', $formatter->format('10.1 '));
	Assert::same('10,1  cm', $formatter->format(' 10.1 '));
});

test(function () {
	$prefix = '~';
	$formatter = new NumberFormatter(NULL, $prefix);
	Assert::same('~ 10,1', $formatter->format('10.1'));

	$prefix = ' ~';
	$formatter = new NumberFormatter(NULL, $prefix);
	Assert::same('~ 10,1', $formatter->format('10.1'));
});

test(function () {
	$prefix = '~';
	$suffix = 'cm';
	$formatter = new NumberFormatter($suffix, $prefix);
	Assert::same('~ 10,1 cm', $formatter->format('10.1'));
});

test(function () {
	$prefix1 = '~1';
	$prefix2 = '~2';
	$suffix1 = 'cm1';
	$suffix2 = 'cm2';
	$formatter = new NumberFormatter($suffix1, $prefix2);
	$formatter->setSuffix($suffix2);
	$formatter->setPrefix($prefix2);
	Assert::same('~2 10,1 cm2', $formatter->format('10.1'));
});

test(function () {
	$formatter = new NumberFormatter();
	$formatter->setPoint('.');
	Assert::same('10.1', $formatter->format('10.1'));
});

test(function () {
	$formatter = new NumberFormatter();
	$formatter->setDecimals(2);
	Assert::same('10,12', $formatter->format('10.123'));
	Assert::same('10,13', $formatter->format('10.125'));

	$formatter->setDecimals(-2);
	Assert::same('12 500', $formatter->format('12534.20'));

	$formatter->setDecimals(-2);
	Assert::same('12 534,222', $formatter->format('12534.222', 3));
	Assert::same('12 534,23', $formatter->format('12534.225', 2));
});

test(function () {
	$prefix = '~';
	$formatter = new NumberFormatter(NULL, $prefix);
	$formatter->setSpaces(FALSE);
	Assert::same('~10,1', $formatter->format('10.1'));

	$postfix = 'cm';
	$formatter = new NumberFormatter($postfix, NULL);
	$formatter->setSpaces(FALSE);
	Assert::same('10,1cm', $formatter->format('10.1'));
});

test(function () {
	$formatter = new NumberFormatter();
	$formatter->setThousands('-');
	Assert::same('10-000', $formatter->format('10000'));
});

test(function () {
	$formatter = new NumberFormatter();
	$formatter->setZeros(FALSE);
	Assert::same('10,1', $formatter->format('10.100'));

	$formatter->setZeros(TRUE);
	Assert::same('10,10', $formatter->format('10.100'));

	$formatter->setDecimals(3);
	$formatter->setZeros(TRUE);
	Assert::same('10,100', $formatter->format('10.100'));
});

test(function () {
	$formatter = new NumberFormatter();
	$formatter->setStrict(TRUE);
	Assert::throws(function () use ($formatter) {
		$formatter->format('word..');
	}, InvalidArgumentException::class);

	$formatter->setStrict(FALSE);
	Assert::same('word', $formatter->format('word'));
});

test(function () {
	$formatter = new NumberFormatter();
	$formatter->setCallback(function ($prefix, $value, $suffix) {
		return [$prefix, $value, $suffix];
	});

	$res = $formatter->format('10.1');
	Assert::same(NULL, $res[0]);
	Assert::same('10,1', $res[1]);
	Assert::same(NULL, $res[2]);
});

test(function () {
	$formatter = new NumberFormatter();

	$p = $formatter->prototype();
	Assert::equal($p, $formatter->prototype());

	$p->setName('div');
	Assert::same('div', $p->getName());
});

test(function () {
	$formatter = new NumberFormatter();

	$p = $formatter->prototype();
	$p->setName('div');
	Assert::same('<div>10,1</div>', (string) $formatter->formatHtml('10.1'));
});
