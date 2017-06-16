<?php

namespace Contributte\Latte\DI;

use Contributte\Latte\Exception\Runtime\LatteDefinitionNotFoundException;
use Contributte\Latte\Macros\VersionMacros;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\PhpLiteral;

/**
 * @author Milan Felix Sulc <sulcmil@gmail.com>
 */
class VersionExtension extends CompilerExtension
{

	/** @var array */
	private $defaults = [
		'debug' => FALSE,
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

		if ($config['debug'] === TRUE) {
			$config['rev'] = md5(microtime() . mt_rand(0, 100));
			$config['build'] = md5(microtime() . mt_rand(0, 100));
			$config['v'] = md5(microtime() . mt_rand(0, 100));
		}

		$builder->getDefinition('latte.latteFactory')
			->addSetup('?->onCompile[] = function ($engine) { ?::install($engine->getCompiler(), ?); }', [
				'@self',
				new PhpLiteral(VersionMacros::class),
				$config,
			]);
	}

}
