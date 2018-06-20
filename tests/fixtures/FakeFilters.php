<?php declare(strict_types = 1);

namespace Tests\Fixtures;

use Contributte\Latte\Filters\FiltersProvider;

final class FakeFilters implements FiltersProvider
{

	/**
	 * @return callable[]
	 */
	public function getFilters(): array
	{
		return [
			'say' => function ($hi) {
				return sprintf('Hi %s!', $hi);
			},
		];
	}

}
