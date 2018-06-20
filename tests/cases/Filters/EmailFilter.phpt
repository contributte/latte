<?php declare(strict_types = 1);

/**
 * @Test Filters\EmailFilter
 */

use Contributte\Latte\Filters\EmailFilter;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

test(function (): void {
	$email = EmailFilter::filter('latte@contributte.org');
	Assert::equal('<a href="mailto:latte@contributte.org" >latte@contributte.org</a>', (string) $email);

	$email = EmailFilter::filter('latte@contributte.org', EmailFilter::ENCODE_DRUPAL);
	Assert::equal('<a href="mailto:latte[at]contributte.org" >latte[at]contributte.org</a>', (string) $email);

	$email = EmailFilter::filter('latte@contributte.org', EmailFilter::ENCODE_HEX);
	Assert::equal('<a href="&#109;&#97;&#105;&#108;&#116;&#111;&#58;%6c%61%74%74%65@%63%6f%6e%74%72%69%62%75%74%74%65.%6f%72%67" >&#x6c;&#x61;&#x74;&#x74;&#x65;&#x40;&#x63;&#x6f;&#x6e;&#x74;&#x72;&#x69;&#x62;&#x75;&#x74;&#x74;&#x65;&#x2e;&#x6f;&#x72;&#x67;</a>', (string) $email);

	$email = EmailFilter::filter('latte@contributte.org', EmailFilter::ENCODE_JAVASCRIPT);
	Assert::equal('<script type="text/javascript">eval(unescape(\'%64%6f%63%75%6d%65%6e%74%2e%77%72%69%74%65%28%27%3c%61%20%68%72%65%66%3d%22%6d%61%69%6c%74%6f%3a%6c%61%74%74%65%40%63%6f%6e%74%72%69%62%75%74%74%65%2e%6f%72%67%22%20%3e%6c%61%74%74%65%40%63%6f%6e%74%72%69%62%75%74%74%65%2e%6f%72%67%3c%2f%61%3e%27%29%3b\'))</script>', (string) $email);

	$email = EmailFilter::filter('latte@contributte.org', EmailFilter::ENCODE_JAVASCRIPT_CHARCODE);
	Assert::equal('<script type="text/javascript" language="javascript">
{document.write(String.fromCharCode(60,97,32,104,114,101,102,61,34,109,97,105,108,116,111,58,108,97,116,116,101,64,99,111,110,116,114,105,98,117,116,116,101,46,111,114,103,34,32,62,108,97,116,116,101,64,99,111,110,116,114,105,98,117,116,116,101,46,111,114,103,60,47,97,62))}
</script>
', (string) $email);

	$email = EmailFilter::filter('latte@contributte.org', EmailFilter::ENCODE_TEXY);
	Assert::equal('<a href="mailto:latte<!-- ANTISPAM -->&#64;<!-- /ANTISPAM -->contributte.org" >latte<!-- ANTISPAM -->&#64;<!-- /ANTISPAM -->contributte.org</a>', (string) $email);

	$email = EmailFilter::filter('latte@contributte.org', EmailFilter::ENCODE_DRUPAL, 'my@email.cz');
	Assert::equal('<a href="mailto:latte[at]contributte.org" >my@email.cz</a>', (string) $email);
});
