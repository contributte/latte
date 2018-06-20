<?php declare(strict_types = 1);

namespace Contributte\Latte\Filters;

interface FiltersProvider
{

	/**
	 * @return callable[]
	 */
	public function getFilters(): array;

}
