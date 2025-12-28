<?php declare(strict_types = 1);

namespace Contributte\Latte\Replacus;

use Latte\ContentType;
use Latte\Engine;
use Latte\Loaders\StringLoader;

/**
 * Simple string replacer using Latte templating engine.
 * Replaces placeholders like {$variable} with provided values.
 */
class Replacus
{

	private Engine $latte;

	public function __construct(?Engine $latte = null)
	{
		$this->latte = $latte ?? $this->createDefaultEngine();
	}

	public static function create(): self
	{
		return new self();
	}

	/**
	 * @param mixed[] $args
	 */
	public function replace(string $input, array $args = []): string
	{
		return $this->latte->renderToString($input, $args);
	}

	public function addFilter(string $name, callable $callback): self
	{
		$this->latte->addFilter($name, $callback);

		return $this;
	}

	public function getLatte(): Engine
	{
		return $this->latte;
	}

	private function createDefaultEngine(): Engine
	{
		$latte = new Engine();
		$latte->setLoader(new StringLoader());
		$latte->setAutoRefresh(false);
		$latte->setContentType(ContentType::Html);

		return $latte;
	}

}
