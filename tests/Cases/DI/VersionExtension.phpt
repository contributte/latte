<?php declare(strict_types = 1);

use Contributte\Latte\DI\VersionExtension;
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
		$compiler->addExtension('version', new VersionExtension());
		$compiler->loadConfig(FileMock::create('
		version:
			rev: 1
			build: 2
			v: 3
		', 'neon'));
	}, 1);

	/** @var Container $container */
	$container = new $class();

	/** @var ILatteFactory $latteFactory */
	$latteFactory = $container->getByType(ILatteFactory::class);

	Assert::equal('123', $latteFactory->create()->renderToString(FileMock::create('{rev}{build}{v}', 'latte')));
});

Toolkit::test(function (): void {
	$loader = new ContainerLoader(Environment::getTestDir(), true);
	$class = $loader->load(function (Compiler $compiler): void {
		$compiler->addExtension('latte', new LatteExtension(Environment::getTestDir()));
		$compiler->addExtension('version', new VersionExtension());
		$compiler->loadConfig(FileMock::create('
		version:
			generated: true
			rev: 1
			build: 2
			v: 3
		', 'neon'));
	}, 2);

	/** @var Container $container */
	$container = new $class();

	/** @var ILatteFactory $latteFactory */
	$latteFactory = $container->getByType(ILatteFactory::class);

	Assert::notEqual('123', $latteFactory->create()->renderToString(FileMock::create('{rev}{build}{v}', 'latte')));
});
