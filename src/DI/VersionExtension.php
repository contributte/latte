<?php declare(strict_types = 1);

namespace Contributte\Latte\DI;

use Contributte\Latte\Exception\LogicalException;
use Contributte\Latte\Extensions\VersionExtension as LatteVersionExtension;
use Nette\Bridges\ApplicationLatte\LatteFactory;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\FactoryDefinition;
use Nette\DI\Definitions\Statement;
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

		if ($builder->getByType(LatteFactory::class) === null) {
			throw new LogicalException('You have to register LatteFactoryfirst.');
		}

		$factoryDefinition = $builder->getDefinitionByType(LatteFactory::class);
		assert($factoryDefinition instanceof FactoryDefinition);

		if ($config->generated) {
			$config->rev = md5(microtime() . random_int(0, 100));
			$config->build = md5(microtime() . random_int(0, 100));
			$config->v = md5(microtime() . random_int(0, 100));
		}

		$factoryDefinition
			->getResultDefinition()
			->addSetup('addExtension', [new Statement(LatteVersionExtension::class, [(array) $config])]);
	}

}
