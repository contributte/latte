<?php declare(strict_types = 1);

namespace Contributte\Latte\DI;

use Contributte\Latte\Exception\LogicalException;
use Contributte\Latte\Extensions\CdnExtension as LatteCdnExtension;
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
class CdnExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'url' => Expect::string()->default(''),
			'cacheBusting' => Expect::anyOf('time', false)->default(false),
		]);
	}

	public function beforeCompile(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->config;

		if ($builder->getByType(LatteFactory::class) === null) {
			throw new LogicalException('You have to register LatteFactory first.');
		}

		$factoryDefinition = $builder->getDefinitionByType(LatteFactory::class);
		assert($factoryDefinition instanceof FactoryDefinition);

		$factoryDefinition
			->getResultDefinition()
			->addSetup('addExtension', [new Statement(LatteCdnExtension::class, [(array) $config])]);
	}

}
