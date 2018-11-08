<?php declare(strict_types = 1);

namespace Contributte\Latte\Filters;

use Contributte\Latte\Exception\Logical\FileNotFoundException;

class FilectimeFilter
{

	public static function filter(string $file, string $format = 'U'): string
	{
		return sprintf('%s?v=%s', $file, self::filectime($file, $format));
	}

	public static function filectime(string $file, string $format = 'U'): string
	{
		if (!file_exists($file)) {
			throw new FileNotFoundException(sprintf('File "%s" not found', $file));
		}

		return date($format, (int) filectime($file));
	}

}
