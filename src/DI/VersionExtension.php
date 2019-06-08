<?php declare(strict_types = 1);

namespace Contributte\Latte\DI;

use Contributte\Latte\Exception\Runtime\LatteDefinitionNotFoundException;
use Contributte\Latte\Macros\VersionMacros;
use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\FactoryDefinition;
use Nette\PhpGenerator\PhpLiteral;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use stdClass;

/**
 * @property-read stdClass $config
 */
class VersionExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'generated' => Expect::bool(false),
			'rev' => Expect::anyOf(Expect::int(), Expect::string()),
			'build' => Expect::anyOf(Expect::int(), Expect::string()),
			'v' => Expect::anyOf(Expect::int(), Expect::string()),
		]);
	}

	public function beforeCompile(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->config;

		if ($builder->getByType(ILatteFactory::class) === null) {
			throw new LatteDefinitionNotFoundException();
		}

		$factoryDefinition = $builder->getDefinitionByType(ILatteFactory::class);
		assert($factoryDefinition instanceof FactoryDefinition);

		if ($config->generated) {
			$config->rev = md5(microtime() . random_int(0, 100));
			$config->build = md5(microtime() . random_int(0, 100));
			$config->v = md5(microtime() . random_int(0, 100));
		}

		$factoryDefinition
			->getResultDefinition()
			->addSetup('?->onCompile[] = function ($engine) { ?::install($engine->getCompiler(), ?); }', [
				'@self',
				new PhpLiteral(VersionMacros::class),
				(array) $config,
			]);
	}

}
