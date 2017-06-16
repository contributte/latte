<?php

namespace Contributte\Latte\Formatters;

use Contributte\Latte\Exception\Logical\InvalidArgumentException;
use Nette\Utils\Html;

/**
 * @author Milan Felix Sulc <sulcmil@gmail.com>
 */
class NumberFormatter
{

	/** @var mixed */
	private $rawValue;

	/** @var string */
	private $thousands = ' ';

	/** @var int */
	private $decimals = 2;

	/** @var string */
	private $point = ',';

	/** @var callable */
	private $callback;

	/** @var bool */
	private $zeros = FALSE;

	/** @var bool */
	private $strict = TRUE;

	/** @var bool */
	private $spaces = TRUE;

	/** @var string */
	private $prefix;

	/** @var string */
	private $suffix;

	/** @var Html */
	private $wrapper;

	/**
	 * @param string $suffix
	 * @param string $prefix
	 */
	public function __construct($suffix = NULL, $prefix = NULL)
	{
		$this->suffix = $suffix;
		$this->prefix = $prefix;

		$this->wrapper = Html::el();
	}

	/**
	 * SETTERS *****************************************************************
	 */

	/**
	 * @param int $number
	 * @return static
	 */
	public function setDecimals($number)
	{
		$this->decimals = intval($number);

		return $this;
	}

	/**
	 * @param string $separator
	 * @return static
	 */
	public function setPoint($separator)
	{
		$this->point = $separator;

		return $this;
	}

	/**
	 * @param string $separator
	 * @return static
	 */
	public function setThousands($separator)
	{
		$this->thousands = $separator;

		return $this;
	}

	/**
	 * @param boolean $show
	 * @return static
	 */
	public function setZeros($show = TRUE)
	{
		$this->zeros = (bool) $show;

		return $this;
	}

	/**
	 * @param string $suffix
	 * @return static
	 */
	public function setSuffix($suffix)
	{
		$this->suffix = $suffix;

		return $this;
	}

	/**
	 * @param string $prefix
	 * @return static
	 */
	public function setPrefix($prefix)
	{
		$this->prefix = $prefix;

		return $this;
	}

	/**
	 * @param boolean $throw
	 * @return static
	 */
	public function setStrict($throw = TRUE)
	{
		$this->strict = (bool) $throw;

		return $this;
	}

	/**
	 * @param boolean $display
	 * @return static
	 */
	public function setSpaces($display = TRUE)
	{
		$this->spaces = (bool) $display;

		return $this;
	}

	/**
	 * @param callable $callback
	 * @return static
	 */
	public function setCallback(callable $callback)
	{
		$this->callback = $callback;

		return $this;
	}

	/**
	 * GETTERS *****************************************************************
	 */

	/**
	 * @return mixed
	 */
	public function getRawValue()
	{
		return $this->rawValue;
	}

	/**
	 * @return Html
	 */
	public function prototype()
	{
		return $this->wrapper;
	}

	/**
	 * FORMAT ******************************************************************
	 */

	/**
	 * @param mixed $value
	 * @param int $decimals
	 * @return mixed
	 */
	public function format($value, $decimals = NULL)
	{
		$value = trim($value);
		$value = str_replace(',', '.', $value);

		if (!is_numeric($value)) {
			if ($this->strict) {
				throw new InvalidArgumentException('Value must be numeric');
			} else {
				return $value;
			}
		}

		$this->rawValue = $value;

		if ($decimals == NULL) {
			$decimals = $this->decimals;
		}

		if ($decimals < 0) {
			$value = round($value, $decimals);
			$decimals = 0;
		}

		$number = number_format((float) $value, $decimals, $this->point, $this->thousands);

		if ($decimals > 0 && !$this->zeros) {
			$number = rtrim(rtrim($number, '0'), $this->point);
		}

		if ($this->callback) {
			return call_user_func_array($this->callback, [$this->prefix, $number, $this->suffix]);
		} else if ($this->spaces) {
			return trim(sprintf('%s %s %s', $this->prefix, $number, $this->suffix));
		} else {
			return trim(sprintf('%s%s%s', $this->prefix, $number, $this->suffix));
		}
	}

	/**
	 * @param int|float $value
	 * @param int $decimals
	 * @return Html
	 */
	public function formatHtml($value, $decimals = NULL)
	{
		$wrapper = clone $this->wrapper;
		$wrapper->setHtml($this->format($value, $decimals));

		return $wrapper;
	}

}
