<?php declare(strict_types = 1);

use Contributte\Latte\Filters\EmailFilter;
use Latte\Engine;
use Nette\DI\Compiler;
use Nette\DI\Config\Loader;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Tester\Assert;
use Tester\FileMock;

require __DIR__ . '/../../bootstrap.php';

$template = '<a href="mailto:%s" >%s</a>';

Toolkit::test(function () use ($template): void {
	$loader = new ContainerLoader(TEMP_DIR, true);
	$class = $loader->load(function (Compiler $compiler): void {
		$loader = new Loader();
		$config = $loader->load(FileMock::create('
		services:
			latteFactory:
				class: Latte\Engine
				setup:
					- addFilter(email, [Contributte\Latte\Filters\EmailFilter, filter])
', 'neon'));
		$compiler->addConfig($config);
	}, 1);

	/** @var Container $container */
	$container = new $class();

	/** @var Engine $latte */
	$latte = $container->getService('latteFactory');

	Assert::equal(sprintf($template, 'my[at]email.com', 'my[at]email.com'), (string) $latte->invokeFilter('email', ['my@email.com', EmailFilter::ENCODE_DRUPAL]));
});
