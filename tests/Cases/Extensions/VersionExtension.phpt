<?php declare(strict_types = 1);

use Contributte\Latte\Extensions\VersionExtension;
use Contributte\Tester\Environment;
use Contributte\Tester\Toolkit;
use Latte\Engine;
use Latte\Loaders\StringLoader;
use Tester\Assert;
use Tester\FileMock;

require_once __DIR__ . '/../../bootstrap.php';

Toolkit::test(function (): void {
	$latte = new Engine();
	$latte->setTempDirectory(Environment::getTestDir());
	$latte->setLoader(new StringLoader());
	$latte->addExtension(new VersionExtension([
		'rev' => 1,
		'build' => 2,
		'v' => 3,
	]));

	Assert::equal('1', $latte->renderToString('{rev}'));
	Assert::equal('2', $latte->renderToString('{build}'));
	Assert::equal('3', $latte->renderToString('{v}'));
	Assert::equal('123', $latte->renderToString('{rev}{build}{v}'));
});

Toolkit::test(function (): void {
	$latte = new Engine();
	$latte->setTempDirectory(Environment::getTestDir());
	$latte->addExtension(new VersionExtension([
		'rev' => 1,
		'build' => 2,
		'v' => 3,
	]));

	Assert::equal('1', $latte->renderToString(FileMock::create('{rev}', 'latte')));
	Assert::equal('2', $latte->renderToString(FileMock::create('{build}', 'latte')));
	Assert::equal('3', $latte->renderToString(FileMock::create('{v}', 'latte')));
	Assert::equal('123', $latte->renderToString(FileMock::create('{rev}{build}{v}', 'latte')));
});
