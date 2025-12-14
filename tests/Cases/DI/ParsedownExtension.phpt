<?php declare(strict_types = 1);

use Contributte\Latte\DI\ParsedownExtension;
use Contributte\Latte\Filters\ParsedownExtraAdapter;
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
require_once __DIR__ . '/../../Fixtures/FakeParsedownExtra.php';

// Test DI extension with default config
Toolkit::test(function (): void {
	$loader = new ContainerLoader(Environment::getTestDir(), true);
	$class = $loader->load(function (Compiler $compiler): void {
		$compiler->addExtension('latte', new LatteExtension(Environment::getTestDir()));
		$compiler->addExtension('parsedown', new ParsedownExtension());
	}, 1);

	/** @var Container $container */
	$container = new $class();

	// Test adapter is registered
	$adapter = $container->getByType(ParsedownExtraAdapter::class);
	Assert::type(ParsedownExtraAdapter::class, $adapter);

	// Test filter is registered
	/** @var ILatteFactory $latteFactory */
	$latteFactory = $container->getByType(ILatteFactory::class);
	$result = $latteFactory->create()->renderToString(FileMock::create('{="Hello World"|parsedown}', 'latte'));
	Assert::equal('<p>Hello World</p>', $result);
});

// Test DI extension with custom filter name
Toolkit::test(function (): void {
	$loader = new ContainerLoader(Environment::getTestDir(), true);
	$class = $loader->load(function (Compiler $compiler): void {
		$compiler->addExtension('latte', new LatteExtension(Environment::getTestDir()));
		$compiler->addExtension('parsedown', new ParsedownExtension());
		$compiler->loadConfig(FileMock::create('
		parsedown:
			filter: markdown
		', 'neon'));
	}, 2);

	/** @var Container $container */
	$container = new $class();

	/** @var ILatteFactory $latteFactory */
	$latteFactory = $container->getByType(ILatteFactory::class);
	$result = $latteFactory->create()->renderToString(FileMock::create('{="Hello World"|markdown}', 'latte'));
	Assert::equal('<p>Hello World</p>', $result);
});

// Test DI extension with block syntax
Toolkit::test(function (): void {
	$loader = new ContainerLoader(Environment::getTestDir(), true);
	$class = $loader->load(function (Compiler $compiler): void {
		$compiler->addExtension('latte', new LatteExtension(Environment::getTestDir()));
		$compiler->addExtension('parsedown', new ParsedownExtension());
	}, 3);

	/** @var Container $container */
	$container = new $class();

	/** @var ILatteFactory $latteFactory */
	$latteFactory = $container->getByType(ILatteFactory::class);
	$result = $latteFactory->create()->renderToString(FileMock::create('{block|parsedown}Hello World{/block}', 'latte'));
	Assert::equal('<p>Hello World</p>', $result);
});
