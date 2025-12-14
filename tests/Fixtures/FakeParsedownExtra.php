<?php declare(strict_types = 1);

namespace Tests\Fixtures;

/**
 * Fake ParsedownExtra for testing purposes
 */
class FakeParsedownExtra
{

	public function parse(string $text): string
	{
		return '<p>' . $text . '</p>';
	}

	public function line(string $line): string
	{
		return $line;
	}

}

// Register as ParsedownExtra if the real one is not available
if (!class_exists('ParsedownExtra')) {
	class_alias(FakeParsedownExtra::class, 'ParsedownExtra');
}
