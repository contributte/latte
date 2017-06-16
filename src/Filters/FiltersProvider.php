<?php

namespace Contributte\Latte\Filters;

interface FiltersProvider
{

	/**
	 * @return callable[]
	 */
	public function getFilters();

}
