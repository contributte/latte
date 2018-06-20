<?php declare(strict_types = 1);

namespace Contributte\Latte\Macros;

use Contributte\Latte\Exception\Logical\InvalidArgumentException;
use Latte\Compiler;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\PhpWriter;

class VersionMacros extends MacroSet
{

	/** @var mixed[] */
	private $config;

	/**
	 * @param mixed[] $config
	 */
	public function __construct(Compiler $compiler, array $config)
	{
		parent::__construct($compiler);
		$this->config = $config;
	}

	/**
	 * @param mixed[] $config
	 */
	public static function install(Compiler $compiler, array $config): self
	{
		$me = new self($compiler, $config);

		$me->addMacro('rev', [$me, 'macroRev']);
		$me->addMacro('build', [$me, 'macroBuild']);
		$me->addMacro('v', [$me, 'macroV']);

		return $me;
	}

	public function macroRev(MacroNode $node, PhpWriter $writer): string
	{
		if (!isset($this->config['rev'])) {
			throw new InvalidArgumentException('Config field "rev" is not filled');
		}

		return $writer->write(sprintf('echo "%s";', $this->config['rev']));
	}

	public function macroBuild(MacroNode $node, PhpWriter $writer): string
	{
		if (!isset($this->config['build'])) {
			throw new InvalidArgumentException('Config field "build" is not filled');
		}

		return $writer->write(sprintf('echo "%s";', $this->config['build']));
	}

	public function macroV(MacroNode $node, PhpWriter $writer): string
	{
		if (!isset($this->config['v'])) {
			throw new InvalidArgumentException('Config field "v" is not filled');
		}

		return $writer->write(sprintf('echo "%s";', $this->config['v']));
	}

}
