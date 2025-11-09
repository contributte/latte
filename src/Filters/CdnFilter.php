<?php declare(strict_types = 1);

namespace Contributte\Latte\Filters;

class CdnFilter
{

	/**
	 * @param array{url?: string, cacheBusting?: string|false} $parameters
	 */
	public static function filter(string $path, array $parameters = []): string
	{
		$baseUrl = $parameters['url'] ?? '';
		$url = rtrim($baseUrl, '/') . '/' . ltrim($path, '/');

		if (($parameters['cacheBusting'] ?? false) === 'time') {
			$separator = str_contains($url, '?') ? '&' : '?';
			$url .= $separator . 'time=' . time();
		}

		return $url;
	}

}
