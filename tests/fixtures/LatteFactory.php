<?php

namespace Tests\Fixtures;

use Latte\Engine;
use Nette\Bridges\ApplicationLatte\ILatteFactory;

final class LatteFactory implements ILatteFactory
{

	/**
	 * @return Engine
	 */
	public function create()
	{
		return new Engine();
	}

}
