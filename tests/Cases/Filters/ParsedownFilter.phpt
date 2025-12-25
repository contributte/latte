<?php declare(strict_types = 1);

use Contributte\Latte\Exception\LogicalException;
use Contributte\Latte\Filters\ParsedownExtraAdapter;
use Contributte\Latte\Filters\ParsedownFilter;
use Latte\ContentType;
use Latte\Runtime\FilterInfo;
use Tester\Assert;
use Tests\Fixtures\FakeParsedownExtra;

require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../Fixtures/FakeParsedownExtra.php';

// Test basic filter application
Toolkit::test(function (): void {
	$adapter = new ParsedownExtraAdapter(new FakeParsedownExtra());
	$filter = new ParsedownFilter($adapter);

	$info = new FilterInfo(ContentType::Html);
	$result = $filter->apply($info, 'Hello World');

	Assert::equal('<p>Hello World</p>', $result);
});

// Test filter works with Text content type and converts to Html
Toolkit::test(function (): void {
	$adapter = new ParsedownExtraAdapter(new FakeParsedownExtra());
	$filter = new ParsedownFilter($adapter);

	$info = new FilterInfo(ContentType::Text);
	$result = $filter->apply($info, 'Hello World');

	Assert::equal('<p>Hello World</p>', $result);
	Assert::equal(ContentType::Html, $info->contentType);
});

// Test filter throws exception for incompatible content type (e.g., JavaScript)
Toolkit::test(function (): void {
	$adapter = new ParsedownExtraAdapter(new FakeParsedownExtra());
	$filter = new ParsedownFilter($adapter);

	$info = new FilterInfo(ContentType::JavaScript);

	Assert::exception(function () use ($filter, $info): void {
		$filter->apply($info, 'Hello World');
	}, LogicalException::class, 'Filter |parsedown used in incompatible content type.');
});

// Test filter with markdown-like content
Toolkit::test(function (): void {
	$adapter = new ParsedownExtraAdapter(new FakeParsedownExtra());
	$filter = new ParsedownFilter($adapter);

	$info = new FilterInfo(ContentType::Html);
	$result = $filter->apply($info, '# Heading');

	Assert::equal('<p># Heading</p>', $result);
});
