<?php

namespace Contributte\Latte\DI;

use Contributte\Latte\Exception\Runtime\LatteDefinitionNotFoundException;
use Contributte\Latte\Filters\FiltersProvider;
use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Nette\DI\CompilerExtension;

/**
 * @author Milan Felix Sulc <sulcmil@gmail.com>
 * @author Tomas Votruba <info@tomasvotruba.cz>
 */
class FiltersExtension extends CompilerExtension
{

	/**
	 * Decorate services
	 *
	 * @return void
	 */
	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();

		if ($builder->getByType(ILatteFactory::class) === NULL) {
			throw new LatteDefinitionNotFoundException();
		}

		$latte = $builder->getDefinitionByType(ILatteFactory::class);
		$filters = $builder->findByType(FiltersProvider::class);

		foreach ($filters as $definition) {
			$latte->addSetup(
				'foreach (?->getFilters() as $name => $callback) {
					?->addFilter($name, $callback);
				}',
				[$definition, '@self']
			);
		}
	}

}
