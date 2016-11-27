<?php

namespace Contributte\Latte\Macros;

use Contributte\Latte\Exception\Logical\InvalidArgumentException;
use Latte\Compiler;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\PhpWriter;

final class VersionMacros extends MacroSet
{

	/** @var array */
	private $config;

	/**
	 * @param Compiler $compiler
	 * @param array $config
	 */
	public function __construct(Compiler $compiler, array $config)
	{
		parent::__construct($compiler);
		$this->config = $config;
	}


	/**
	 * @param Compiler $compiler
	 * @param array $config
	 * @return VersionMacros
	 */
	public static function install(Compiler $compiler, array $config)
	{
		$me = new self($compiler, $config);

		$me->addMacro('rev', [$me, 'macroRev']);
		$me->addMacro('build', [$me, 'macroBuild']);
		$me->addMacro('v', [$me, 'macroV']);

		return $me;
	}

	/**
	 * @param MacroNode $node
	 * @param PhpWriter $writer
	 * @return string
	 */
	public function macroRev(MacroNode $node, PhpWriter $writer)
	{
		if (!isset($this->config['rev'])) {
			throw new InvalidArgumentException('Config field "rev" is not filled');
		}

		return $writer->write(sprintf('echo %s;', $this->config['rev']));
	}

	/**
	 * @param MacroNode $node
	 * @param PhpWriter $writer
	 * @return string
	 */
	public function macroBuild(MacroNode $node, PhpWriter $writer)
	{
		if (!isset($this->config['build'])) {
			throw new InvalidArgumentException('Config field "build" is not filled');
		}

		return $writer->write(sprintf('echo %s;', $this->config['build']));
	}

	/**
	 * @param MacroNode $node
	 * @param PhpWriter $writer
	 * @return string
	 */
	public function macroV(MacroNode $node, PhpWriter $writer)
	{
		if (!isset($this->config['v'])) {
			throw new InvalidArgumentException('Config field "v" is not filled');
		}

		return $writer->write(sprintf('echo %s;', $this->config['v']));
	}

}
