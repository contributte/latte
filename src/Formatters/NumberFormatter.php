<?php declare(strict_types = 1);

namespace Contributte\Latte\Formatters;

use Contributte\Latte\Exception\Logical\InvalidArgumentException;
use Nette\Utils\Html;

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

	/** @var callable|null */
	private $callback;

	/** @var bool */
	private $zeros = false;

	/** @var bool */
	private $strict = true;

	/** @var bool */
	private $spaces = true;

	/** @var string */
	private $prefix;

	/** @var string */
	private $suffix;

	/** @var Html */
	private $wrapper;

	public function __construct(string $suffix = '', string $prefix = '')
	{
		$this->suffix = $suffix;
		$this->prefix = $prefix;

		$this->wrapper = Html::el();
	}

	public function setDecimals(int $number): self
	{
		$this->decimals = $number;
		return $this;
	}

	public function setPoint(string $separator): self
	{
		$this->point = $separator;
		return $this;
	}

	public function setThousands(string $separator): self
	{
		$this->thousands = $separator;
		return $this;
	}

	public function setZeros(bool $show = true): self
	{
		$this->zeros = $show;
		return $this;
	}

	public function setSuffix(string $suffix): self
	{
		$this->suffix = $suffix;
		return $this;
	}

	public function setPrefix(string $prefix): self
	{
		$this->prefix = $prefix;
		return $this;
	}

	public function setStrict(bool $throw = true): self
	{
		$this->strict = $throw;
		return $this;
	}

	public function setSpaces(bool $display = true): self
	{
		$this->spaces = $display;
		return $this;
	}

	public function setCallback(callable $callback): self
	{
		$this->callback = $callback;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getRawValue()
	{
		return $this->rawValue;
	}

	public function prototype(): Html
	{
		return $this->wrapper;
	}

	/**
	 * @param mixed $value
	 * @return mixed
	 */
	public function format($value, ?int $decimals = null)
	{
		if (is_string($value)) {
			$value = trim($value);
			$value = str_replace(',', '.', $value);
		}

		if (!is_numeric($value)) {
			if ($this->strict) {
				throw new InvalidArgumentException('Value must be numeric');
			}

			return $value;
		}

		$this->rawValue = $value;
		$value = (float) $value;

		if ($decimals === null) {
			$decimals = $this->decimals;
		}

		if ($decimals < 0) {
			$value = round($value, $decimals);
			$decimals = 0;
		}

		$number = number_format($value, $decimals, $this->point, $this->thousands);

		if ($decimals > 0 && !$this->zeros) {
			$number = rtrim(rtrim($number, '0'), $this->point);
		}

		if ($this->callback !== null) {
			return call_user_func_array($this->callback, [$this->prefix, $number, $this->suffix]);
		} elseif ($this->spaces) {
			return trim(sprintf('%s %s %s', $this->prefix, $number, $this->suffix));
		} else {
			return trim(sprintf('%s%s%s', $this->prefix, $number, $this->suffix));
		}
	}

	/**
	 * @param mixed $value
	 */
	public function formatHtml($value, ?int $decimals = null): Html
	{
		$wrapper = clone $this->wrapper;
		$wrapper->setHtml($this->format($value, $decimals));

		return $wrapper;
	}

}
