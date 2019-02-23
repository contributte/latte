<?php declare(strict_types = 1);

namespace Contributte\Latte\Filters;

class GravatarFilter
{

	/**
	 * @param string[] $parameters
	 */
	public static function filter(string $email, array $parameters = []): string
	{
		$format = $parameters['format'] ?? 'jpg';
		$style = $parameters['style'] ?? 'retro';
		$size = $parameters['size'] ?? '80';
		return sprintf('https://www.gravatar.com/avatar/%s.%s?default=%s&size=%s', md5($email), $format, $style, $size);
	}

}
