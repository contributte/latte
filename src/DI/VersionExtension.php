<?php

namespace Contributte\Latte\DI;

use Contributte\Latte\Exception\Runtime\LatteDefinitionNotFoundException;
use Contributte\Latte\Macros\VersionMacros;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\PhpLiteral;

/**
 * @author Milan Felix Sulc <sulcmil@gmail.com>
 */
final class VersionExtension extends CompilerExtension
{

	/** @var array */
	private $defaults = [
		'rev' => NULL,
		'build' => NULL,
		'v' => NULL,
	];

	/**
	 * Decorate services
	 *
	 * @return void
	 */
	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);

		if (!$builder->hasDefinition('latte.latteFactory')) {
			throw new LatteDefinitionNotFoundException();
		}

		$builder->getDefinition('latte.latteFactory')
			->addSetup('?->onCompile[] = function ($engine) { ?::install($engine->getCompiler(), ?); }', [
				'@self',
				new PhpLiteral(VersionMacros::class),
				$config,
			]);
	}

}
