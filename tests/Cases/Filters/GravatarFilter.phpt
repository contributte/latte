<?php declare(strict_types = 1);

use Contributte\Latte\Filters\GravatarFilter;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

Toolkit::test(function (): void {
	$url = GravatarFilter::filter('lorem@ipsum.com');
	Assert::equal('https://www.gravatar.com/avatar/067398c3f23785981cd8672e21643405.jpg?default=retro&size=80', $url);

	$url = GravatarFilter::filter('bakon@ipsum.com', [
		'format' => 'png',
		'style' => 'blank',
		'size' => '160',
	]);
	Assert::equal('https://www.gravatar.com/avatar/545cd9dffc907eaed29c3ae1830e9a6b.png?default=blank&size=160', $url);
});
