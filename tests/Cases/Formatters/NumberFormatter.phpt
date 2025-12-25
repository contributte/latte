<?php declare(strict_types = 1);

use Contributte\Latte\Exception\LogicalException;
use Contributte\Latte\Formatters\NumberFormatter;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

Toolkit::test(function (): void {
	$formatter = new NumberFormatter();
	Assert::same('10', $formatter->format(10));
});

Toolkit::test(function (): void {
	$formatter = new NumberFormatter();
	Assert::same('10,1', $formatter->format(10.1));
	Assert::same('10 000', $formatter->format(10000));
});

Toolkit::test(function (): void {
	$formatter = new NumberFormatter();
	Assert::same('10,1', $formatter->format(10.10));
	Assert::same('10,1', $formatter->format(10.10000));
});

Toolkit::test(function (): void {
	$formatter = new NumberFormatter();
	Assert::same('10,1', $formatter->format(' 10.10 '));
	Assert::same('10,1', $formatter->format('10.10 '));
	Assert::same('10,1', $formatter->format(' 10.10'));
});

Toolkit::test(function (): void {
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

Toolkit::test(function (): void {
	$prefix = '~';
	$formatter = new NumberFormatter('', $prefix);
	Assert::same('~ 10,1', $formatter->format('10.1'));

	$prefix = ' ~';
	$formatter = new NumberFormatter('', $prefix);
	Assert::same('~ 10,1', $formatter->format('10.1'));
});

Toolkit::test(function (): void {
	$prefix = '~';
	$suffix = 'cm';
	$formatter = new NumberFormatter($suffix, $prefix);
	Assert::same('~ 10,1 cm', $formatter->format('10.1'));
});

Toolkit::test(function (): void {
	$prefix1 = '~1';
	$prefix2 = '~2';
	$suffix1 = 'cm1';
	$suffix2 = 'cm2';
	$formatter = new NumberFormatter($suffix1, $prefix1);
	$formatter->setSuffix($suffix2);
	$formatter->setPrefix($prefix2);
	Assert::same('~2 10,1 cm2', $formatter->format('10.1'));
});

Toolkit::test(function (): void {
	$formatter = new NumberFormatter();
	$formatter->setPoint('.');
	Assert::same('10.1', $formatter->format('10.1'));
});

Toolkit::test(function (): void {
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

Toolkit::test(function (): void {
	$prefix = '~';
	$formatter = new NumberFormatter('', $prefix);
	$formatter->setSpaces(false);
	Assert::same('~10,1', $formatter->format('10.1'));

	$postfix = 'cm';
	$formatter = new NumberFormatter($postfix, '');
	$formatter->setSpaces(false);
	Assert::same('10,1cm', $formatter->format('10.1'));
});

Toolkit::test(function (): void {
	$formatter = new NumberFormatter();
	$formatter->setThousands('-');
	Assert::same('10-000', $formatter->format('10000'));
});

Toolkit::test(function (): void {
	$formatter = new NumberFormatter();
	$formatter->setZeros(false);
	Assert::same('10,1', $formatter->format('10.100'));

	$formatter->setZeros(true);
	Assert::same('10,10', $formatter->format('10.100'));

	$formatter->setDecimals(3);
	$formatter->setZeros(true);
	Assert::same('10,100', $formatter->format('10.100'));
});

Toolkit::test(function (): void {
	$formatter = new NumberFormatter();
	$formatter->setStrict(true);
	Assert::throws(function () use ($formatter): void {
		$formatter->format('word..');
	}, LogicalException::class);

	$formatter->setStrict(false);
	Assert::same('word', $formatter->format('word'));
});

Toolkit::test(function (): void {
	$formatter = new NumberFormatter();
	$formatter->setCallback(fn ($prefix, $value, $suffix): array => [$prefix, $value, $suffix]);

	$res = $formatter->format('10.1');
	Assert::same('', $res[0]);
	Assert::same('10,1', $res[1]);
	Assert::same('', $res[2]);
});

Toolkit::test(function (): void {
	$formatter = new NumberFormatter();

	$p = $formatter->prototype();
	Assert::equal($p, $formatter->prototype());

	$p->setName('div');
	Assert::same('div', $p->getName());
});

Toolkit::test(function (): void {
	$formatter = new NumberFormatter();

	$p = $formatter->prototype();
	$p->setName('div');
	Assert::same('<div>10,1</div>', (string) $formatter->formatHtml('10.1'));
});
