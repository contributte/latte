<?php declare(strict_types = 1);

use Contributte\Latte\DI\FiltersExtension;
use Contributte\Tester\Environment;
use Contributte\Tester\Toolkit;
use Nette\Bridges\ApplicationDI\LatteExtension;
use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Tester\Assert;
use Tester\FileMock;

require_once __DIR__ . '/../../bootstrap.php';

Toolkit::test(function (): void {
	$loader = new ContainerLoader(Environment::getTestDir(), true);
	$class = $loader->load(function (Compiler $compiler): void {
		$compiler->addExtension('latte', new LatteExtension(Environment::getTestDir()));
		$compiler->addExtension('filters', new FiltersExtension());
		$compiler->loadConfig(FileMock::create('
		services:
			- Tests\Fixtures\FakeFilters
		', 'neon'));
	}, 1);

	/** @var Container $container */
	$container = new $class();

	/** @var ILatteFactory $latteFactory */
	$latteFactory = $container->getByType(ILatteFactory::class);

	Assert::equal('Hi Contributte!', $latteFactory->create()->renderToString(FileMock::create('{="Contributte"|say}', 'latte')));
});
