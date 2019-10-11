<?php declare(strict_types = 1);

/**
 * Test: EmailFilter - Latte parsing
 */

use Contributte\Latte\Filters\EmailFilter;
use Latte\Engine;
use Latte\Loaders\StringLoader;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

$template = '<a href="mailto:%s" >%s</a>';

test(function (): void {
	$email = 'my@email.com';

	$latte = new Engine();
	$latte->setLoader(new StringLoader());
	$output = $latte->renderToString('{$email}', ['email' => 'my@email.com']);

	Assert::equal($email, $output);
});

test(function (): void {
	$email = 'my@email.com';
	$output1 = EmailFilter::filter($email, EmailFilter::ENCODE_DRUPAL);

	$latte = new Engine();
	$latte->setLoader(new StringLoader());
	$latte->addFilter('email', [EmailFilter::class, 'filter']);
	$output2 = $latte->renderToString('{$email|email:drupal}', ['email' => $email]);

	Assert::equal((string) $output1, $output2);
});

test(function () use ($template): void {
	$email = 'my@email.com';
	$output = sprintf($template, 'my[at]email.com', 'my[at]email.com');

	$output1 = EmailFilter::protect($email, EmailFilter::ENCODE_DRUPAL);

	$latte = new Engine();
	$latte->setLoader(new StringLoader());
	$latte->addFilter('email', [EmailFilter::class, 'filter']);
	$output2 = $latte->renderToString('{$email|email:drupal}', ['email' => $email]);

	Assert::equal($output, $output1);
	Assert::equal($output, $output2);
	Assert::equal($output1, $output2);
});
