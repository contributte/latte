<?php declare(strict_types = 1);

namespace Contributte\Latte\DI;

use Contributte\Latte\Exception\LogicalException;
use Contributte\Latte\Filters\FiltersProvider;
use Nette\Bridges\ApplicationLatte\LatteFactory;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\FactoryDefinition;

class FiltersExtension extends CompilerExtension
{

	public function beforeCompile(): void
	{
		$builder = $this->getContainerBuilder();
		if ($builder->getByType(LatteFactory::class) === null) {
			throw new LogicalException('You have to register LatteFactory first.');
		}

		$latte = $builder->getDefinitionByType(LatteFactory::class);
		assert($latte instanceof FactoryDefinition);
		$filters = $builder->findByType(FiltersProvider::class);

		foreach ($filters as $definition) {
			$latte
				->getResultDefinition()
				->addSetup(
					<<<'PHP'
					foreach (?->getFilters() as $name => $callback) {
						?->addFilter($name, $callback);
					}
					PHP,
					[$definition, '@self']
				);
		}
	}

}
