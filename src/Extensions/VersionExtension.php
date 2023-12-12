<?php declare(strict_types = 1);

namespace Contributte\Latte\Extensions;

use Contributte\Latte\Extensions\Node\BuildNode;
use Contributte\Latte\Extensions\Node\RevNode;
use Contributte\Latte\Extensions\Node\VNode;
use Latte\Extension;

class VersionExtension extends Extension
{

	/** @var array<string, string> */
	protected array $config = [];

	/**
	 * @param array<string, string> $config
	 */
	public function __construct(array $config)
	{
		$this->config = $config;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTags(): array
	{
		return [
			'rev' => [RevNode::class, 'create'],
			'build' => [BuildNode::class, 'create'],
			'v' => [VNode::class, 'create'],
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function getProviders(): array
	{
		return [
			'versionRev' => $this->config['rev'] ?? null,
			'versionBuild' => $this->config['build'] ?? null,
			'versionV' => $this->config['v'] ?? null,
		];
	}

}
