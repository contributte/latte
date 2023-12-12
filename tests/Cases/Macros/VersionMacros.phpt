<?php declare(strict_types = 1);

/**
 * @Test Macros\VersionMacros
 */

use Contributte\Latte\Macros\VersionMacros;
use Latte\Engine;
use Latte\Loaders\StringLoader;
use Tester\Assert;
use Tester\FileMock;

require_once __DIR__ . '/../../bootstrap.php';

test(function (): void {
	$latte = new Engine();
	$latte->setTempDirectory(TEMP_DIR);
	$latte->setLoader(new StringLoader());
	$latte->onCompile[] = function (Engine $engine): void {
		VersionMacros::install($engine->getCompiler(), [
			'rev' => 1,
			'build' => 2,
			'v' => 3,
		]);
	};

	Assert::equal('1', $latte->renderToString('{rev}'));
	Assert::equal('2', $latte->renderToString('{build}'));
	Assert::equal('3', $latte->renderToString('{v}'));
	Assert::equal('123', $latte->renderToString('{rev}{build}{v}'));
});

test(function (): void {
	$latte = new Engine();
	$latte->setTempDirectory(TEMP_DIR);
	$latte->onCompile[] = function (Engine $engine): void {
		VersionMacros::install($engine->getCompiler(), [
			'rev' => 1,
			'build' => 2,
			'v' => 3,
		]);
	};

	Assert::equal('1', $latte->renderToString(FileMock::create('{rev}', 'latte')));
	Assert::equal('2', $latte->renderToString(FileMock::create('{build}', 'latte')));
	Assert::equal('3', $latte->renderToString(FileMock::create('{v}', 'latte')));
	Assert::equal('123', $latte->renderToString(FileMock::create('{rev}{build}{v}', 'latte')));
});
