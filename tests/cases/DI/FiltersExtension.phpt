<?php

/**
 * Test: DI\FiltersExtension
 */

use Contributte\Latte\DI\FiltersExtension;
use Nette\Bridges\ApplicationDI\LatteExtension;
use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Tester\Assert;
use Tester\FileMock;

require_once __DIR__ . '/../../bootstrap.php';

test(function () {
	$loader = new ContainerLoader(TEMP_DIR, TRUE);
	$class = $loader->load(function (Compiler $compiler) {
		$compiler->addExtension('latte', new LatteExtension(TEMP_DIR));
		$compiler->addExtension('filters', new FiltersExtension());
		$compiler->loadConfig(FileMock::create('
		services:
			- Tests\Fixtures\FakeFilters
		', 'neon'));
	}, 1);

	/** @var Container $container */
	$container = new $class;

	/** @var ILatteFactory $latteFactory */
	$latteFactory = $container->getByType(ILatteFactory::class);

	Assert::equal('Hi Contributte!', $latteFactory->create()->renderToString(FileMock::create('{="Contributte"|say}', 'latte')));
});
