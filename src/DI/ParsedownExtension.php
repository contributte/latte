<?php declare(strict_types = 1);

namespace Contributte\Latte\DI;

use Contributte\Latte\Exception\LogicalException;
use Contributte\Latte\Filters\ParsedownExtraAdapter;
use Contributte\Latte\Filters\ParsedownFilter;
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
class ParsedownExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'filter' => Expect::string('parsedown'),
		]);
	}

	public function loadConfiguration(): void
	{
		if (!class_exists('ParsedownExtra')) {
			throw new LogicalException('ParsedownExtra class not found. Install erusev/parsedown-extra package.');
		}

		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('adapter'))
			->setFactory(ParsedownExtraAdapter::class);
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
			->addSetup('addFilter', [$config->filter, [new Statement(ParsedownFilter::class), 'apply']]);
	}

}
