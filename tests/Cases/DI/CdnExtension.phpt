<?php declare(strict_types = 1);

use Contributte\Latte\DI\CdnExtension;
use Nette\Bridges\ApplicationDI\LatteExtension;
use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Tester\Assert;
use Tester\FileMock;

require_once __DIR__ . '/../../bootstrap.php';

// Test DI integration with CDN URL
Toolkit::test(function (): void {
	$loader = new ContainerLoader(TEMP_DIR, true);
	$class = $loader->load(function (Compiler $compiler): void {
		$compiler->addExtension('latte', new LatteExtension(TEMP_DIR));
		$compiler->addExtension('cdn', new CdnExtension());
		$compiler->loadConfig(FileMock::create('
		cdn:
			url: https://cdn.example.com
		', 'neon'));
	}, 1);

	/** @var Container $container */
	$container = new $class();

	/** @var ILatteFactory $latteFactory */
	$latteFactory = $container->getByType(ILatteFactory::class);

	Assert::equal('https://cdn.example.com/assets/style.css', $latteFactory->create()->renderToString(FileMock::create('{cdn "assets/style.css"}', 'latte')));
	Assert::equal('https://cdn.example.com/assets/style.css', $latteFactory->create()->renderToString(FileMock::create('{="assets/style.css"|cdn}', 'latte')));
});

// Test DI integration with CDN URL and cacheBusting
Toolkit::test(function (): void {
	$loader = new ContainerLoader(TEMP_DIR, true);
	$class = $loader->load(function (Compiler $compiler): void {
		$compiler->addExtension('latte', new LatteExtension(TEMP_DIR));
		$compiler->addExtension('cdn', new CdnExtension());
		$compiler->loadConfig(FileMock::create('
		cdn:
			url: https://cdn.example.com
			cacheBusting: time
		', 'neon'));
	}, 2);

	/** @var Container $container */
	$container = new $class();

	/** @var ILatteFactory $latteFactory */
	$latteFactory = $container->getByType(ILatteFactory::class);

	$result = $latteFactory->create()->renderToString(FileMock::create('{cdn "assets/style.css"}', 'latte'));
	Assert::true(str_starts_with($result, 'https://cdn.example.com/assets/style.css?time='));

	$result = $latteFactory->create()->renderToString(FileMock::create('{="assets/style.css"|cdn}', 'latte'));
	Assert::true(str_starts_with($result, 'https://cdn.example.com/assets/style.css?time='));
});

// Test DI integration without CDN URL (localhost mode)
Toolkit::test(function (): void {
	$loader = new ContainerLoader(TEMP_DIR, true);
	$class = $loader->load(function (Compiler $compiler): void {
		$compiler->addExtension('latte', new LatteExtension(TEMP_DIR));
		$compiler->addExtension('cdn', new CdnExtension());
		$compiler->loadConfig(FileMock::create('
		cdn:
			url: ""
			cacheBusting: time
		', 'neon'));
	}, 3);

	/** @var Container $container */
	$container = new $class();

	/** @var ILatteFactory $latteFactory */
	$latteFactory = $container->getByType(ILatteFactory::class);

	$result = $latteFactory->create()->renderToString(FileMock::create('{cdn "assets/style.css"}', 'latte'));
	Assert::true(str_starts_with($result, '/assets/style.css?time='));

	$result = $latteFactory->create()->renderToString(FileMock::create('{="assets/style.css"|cdn}', 'latte'));
	Assert::true(str_starts_with($result, '/assets/style.css?time='));
});

// Test DI integration with minimal config
Toolkit::test(function (): void {
	$loader = new ContainerLoader(TEMP_DIR, true);
	$class = $loader->load(function (Compiler $compiler): void {
		$compiler->addExtension('latte', new LatteExtension(TEMP_DIR));
		$compiler->addExtension('cdn', new CdnExtension());
	}, 4);

	/** @var Container $container */
	$container = new $class();

	/** @var ILatteFactory $latteFactory */
	$latteFactory = $container->getByType(ILatteFactory::class);

	Assert::equal('/assets/style.css', $latteFactory->create()->renderToString(FileMock::create('{cdn "assets/style.css"}', 'latte')));
	Assert::equal('/assets/style.css', $latteFactory->create()->renderToString(FileMock::create('{="assets/style.css"|cdn}', 'latte')));
});
