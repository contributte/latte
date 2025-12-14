<?php declare(strict_types = 1);

namespace Contributte\Latte\Filters;

use Contributte\Latte\Exception\LogicalException;
use Latte\ContentType;
use Latte\Runtime\FilterInfo;

class ParsedownFilter
{

	protected ParsedownExtraAdapter $adapter;

	public function __construct(ParsedownExtraAdapter $adapter)
	{
		$this->adapter = $adapter;
	}

	public function apply(FilterInfo $info, mixed $text): mixed
	{
		if ($info->contentType !== null && $info->contentType !== ContentType::Html && $info->contentType !== ContentType::Text) {
			throw new LogicalException('Filter |parsedown used in incompatible content type.');
		}

		$info->contentType = ContentType::Html;

		return $this->adapter->process($text);
	}

}
