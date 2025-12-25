<?php declare(strict_types = 1);

use Tester\Environment;

if (@!include __DIR__ . '/../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer update --dev`';
	exit(1);
}

Environment::setup();

// Create unique temp directory per test file to avoid container cache collisions
$tempDir = __DIR__ . '/tmp/' . getmypid() . '_' . substr(md5($_SERVER['argv'][0] ?? __FILE__), 0, 10);
@mkdir($tempDir, 0777, true);
define('TEMP_DIR', $tempDir);

/**
 * Simple test wrapper for backwards compatibility with contributte/tester
 */
function test(string $description, Closure $test): void
{
	$test();
}

/**
 * Toolkit class for backwards compatibility with contributte/tester
 */
class Toolkit
{

	public static function test(Closure $test): void
	{
		$test();
	}

}
