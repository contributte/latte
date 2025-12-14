<?php declare(strict_types = 1);

namespace Contributte\Latte\Filters;

use ParsedownExtra;

/**
 * ParsedownExtra Adapter
 *
 * @method void onProcess(string $text, ParsedownExtraAdapter $adapter)
 */
class ParsedownExtraAdapter
{

	/** @var callable[] */
	public array $onProcess = [];

	private ParsedownExtra $parsedown;

	public function __construct(?ParsedownExtra $parsedown = null)
	{
		$this->parsedown = $parsedown ?? new ParsedownExtra();
	}

	public function process(mixed $text): mixed
	{
		foreach ($this->onProcess as $callback) {
			$callback($text, $this);
		}

		return $this->parsedown->parse($text);
	}

	public function processLine(mixed $line): string
	{
		foreach ($this->onProcess as $callback) {
			$callback($line, $this);
		}

		return $this->parsedown->line($line);
	}

}
