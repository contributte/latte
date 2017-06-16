<?php

namespace Tests\Fixtures;

use Contributte\Latte\Filters\FiltersProvider;

final class FakeFilters implements FiltersProvider
{

	/**
	 * @return array
	 */
	public function getFilters()
	{
		return [
			'say' => function ($hi) {
				return sprintf('Hi %s!', $hi);
			},
		];
	}

}
