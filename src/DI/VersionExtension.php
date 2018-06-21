<?php declare(strict_types = 1);

namespace Contributte\Latte\DI;

use Contributte\Latte\Exception\Runtime\LatteDefinitionNotFoundException;
use Contributte\Latte\Macros\VersionMacros;
use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\PhpLiteral;

class VersionExtension extends CompilerExtension
{

	/** @var mixed[] */
	private $defaults = [
		'generated' => false,
		'rev' => null,
		'build' => null,
		'v' => null,
	];

	/**
	 * Decorate services
	 */
	public function beforeCompile(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);

		if ($builder->getByType(ILatteFactory::class) === null) {
			throw new LatteDefinitionNotFoundException();
		}

		if ($config['generated'] === true) {
			$config['rev'] = md5(microtime() . random_int(0, 100));
			$config['build'] = md5(microtime() . random_int(0, 100));
			$config['v'] = md5(microtime() . random_int(0, 100));
		}

		$builder->getDefinitionByType(ILatteFactory::class)
			->addSetup('?->onCompile[] = function ($engine) { ?::install($engine->getCompiler(), ?); }', [
				'@self',
				new PhpLiteral(VersionMacros::class),
				$config,
			]);
	}

}
