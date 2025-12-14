<?php declare(strict_types = 1);

use Contributte\Latte\Filters\ParsedownExtraAdapter;
use Contributte\Tester\Toolkit;
use Tester\Assert;
use Tests\Fixtures\FakeParsedownExtra;

require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../Fixtures/FakeParsedownExtra.php';

// Test basic parsing
Toolkit::test(function (): void {
	$adapter = new ParsedownExtraAdapter(new FakeParsedownExtra());
	$result = $adapter->process('Hello World');
	Assert::equal('<p>Hello World</p>', $result);
});

// Test line processing
Toolkit::test(function (): void {
	$adapter = new ParsedownExtraAdapter(new FakeParsedownExtra());
	$result = $adapter->processLine('Hello World');
	Assert::equal('Hello World', $result);
});

// Test onProcess callback
Toolkit::test(function (): void {
	$adapter = new ParsedownExtraAdapter(new FakeParsedownExtra());
	$called = false;
	$adapter->onProcess[] = function (string $text, ParsedownExtraAdapter $a) use (&$called): void {
		$called = true;
		Assert::equal('Test', $text);
	};

	$adapter->process('Test');
	Assert::true($called);
});

// Test onProcess callback for line
Toolkit::test(function (): void {
	$adapter = new ParsedownExtraAdapter(new FakeParsedownExtra());
	$called = false;
	$adapter->onProcess[] = function (string $line, ParsedownExtraAdapter $a) use (&$called): void {
		$called = true;
		Assert::equal('Test Line', $line);
	};

	$adapter->processLine('Test Line');
	Assert::true($called);
});

// Test multiple onProcess callbacks
Toolkit::test(function (): void {
	$adapter = new ParsedownExtraAdapter(new FakeParsedownExtra());
	$callCount = 0;
	$adapter->onProcess[] = function () use (&$callCount): void {
		$callCount++;
	};
	$adapter->onProcess[] = function () use (&$callCount): void {
		$callCount++;
	};

	$adapter->process('Test');
	Assert::equal(2, $callCount);
});
