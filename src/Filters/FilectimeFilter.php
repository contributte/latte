<?php

namespace Contributte\Latte\Filters;

use Contributte\Latte\Exception\Logical\FileNotFoundException;

class FilectimeFilter
{

	/**
	 * @param string $file
	 * @param string $format
	 * @return string
	 */
	public static function filter($file, $format = 'U')
	{
		return sprintf('%s?v=%s', $file, self::filectime($file, $format));
	}

	/**
	 * @param string $file
	 * @param string $format
	 * @return string
	 */
	public static function filectime($file, $format = 'U')
	{
		if (!file_exists($file)) {
			throw new FileNotFoundException(sprintf('File "%s" not found', $file));
		}

		return date($format, filectime($file));
	}

}
