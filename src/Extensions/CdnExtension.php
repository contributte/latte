<?php declare(strict_types = 1);

namespace Contributte\Latte\Extensions;

use Contributte\Latte\Extensions\Node\CdnNode;
use Latte\Extension;

class CdnExtension extends Extension
{

	/**
	 * @param array{url?: string, cacheBusting?: string|false} $config
	 */
	public function __construct(
		private array $config = [],
	)
	{
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTags(): array
	{
		return [
			'cdn' => [CdnNode::class, 'create'],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function getProviders(): array
	{
		return [
			'cdnBuilder' => function (string $path): string {
				$baseUrl = $this->config['url'] ?? '';
				$url = rtrim($baseUrl, '/') . '/' . ltrim($path, '/');

				if (($this->config['cacheBusting'] ?? false) === 'time') {
					$separator = str_contains($url, '?') ? '&' : '?';
					$url .= $separator . 'time=' . time();
				}

				return $url;
			},
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFilters(): array
	{
		return [
			'cdn' => function (string $path): string {
				$baseUrl = $this->config['url'] ?? '';
				$url = rtrim($baseUrl, '/') . '/' . ltrim($path, '/');

				if (($this->config['cacheBusting'] ?? false) === 'time') {
					$separator = str_contains($url, '?') ? '&' : '?';
					$url .= $separator . 'time=' . time();
				}

				return $url;
			},
		];
	}

}
