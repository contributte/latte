<?php

namespace Contributte\Latte\Filters;

use Nette\Utils\Html;

class EmailFilter
{

	// Encoding types
	const ENCODE_JAVASCRIPT = 'javascript';
	const ENCODE_JAVASCRIPT_CHARCODE = 'javascript_charcode';
	const ENCODE_HEX = 'hex';
	const ENCODE_DRUPAL = 'drupal';
	const ENCODE_TEXY = 'texy';

	/**
	 * @param string $address
	 * @param string $encode [optional]
	 * @param string $text [optional]
	 * @return Html
	 */
	public static function filter($address, $encode = NULL, $text = NULL)
	{
		return Html::el()->setHtml(self::protect($address, $encode, $text));
	}

	/**
	 * Smarty {mailto} function plugin
	 *
	 * @param string $address
	 * @param string $encode [optional]
	 * @param string $text [optional]
	 *
	 * @link http://www.smarty.net/manual/en/language.function.mailto.php {mailto}
	 *
	 * @return string
	 */
	public static function protect($address, $encode = NULL, $text = NULL)
	{
		$_text = $text == NULL ? $address : $text;
		$_extra = NULL;
		if ($encode == 'javascript') {
			$string = 'document.write(\'<a href="mailto:' . $address . '" ' . $_extra . '>' . $_text . '</a>\');';
			$js_encode = '';
			for ($x = 0, $_length = strlen($string); $x < $_length; $x++) {
				$js_encode .= '%' . bin2hex($string[$x]);
			}

			return '<script type="text/javascript">eval(unescape(\'' . $js_encode . '\'))</script>';
		} elseif ($encode == 'javascript_charcode') {
			$string = '<a href="mailto:' . $address . '" ' . $_extra . '>' . $_text . '</a>';
			for ($x = 0, $y = strlen($string); $x < $y; $x++) {
				$ord[] = ord($string[$x]);
			}
			$_ret = "<script type=\"text/javascript\" language=\"javascript\">\n"
				. '{document.write(String.fromCharCode('
				. implode(',', $ord)
				. '))'
				. "}\n"
				. "</script>\n";

			return $_ret;
		} elseif ($encode == 'hex') {
			preg_match('!^(.*)(\?.*)$!', $address, $match);
			if (!empty($match[2])) {
				trigger_error('mailto: hex encoding does not work with extra attributes. Try javascript.', E_USER_WARNING);

				return;
			}
			$address_encode = '';
			for ($x = 0, $_length = strlen($address); $x < $_length; $x++) {
				if (preg_match('!\w!u', $address[$x])) {
					$address_encode .= '%' . bin2hex($address[$x]);
				} else {
					$address_encode .= $address[$x];
				}
			}
			$text_encode = '';
			for ($x = 0, $_length = strlen($_text); $x < $_length; $x++) {
				$text_encode .= '&#x' . bin2hex($_text[$x]) . ';';
			}
			$mailto = '&#109;&#97;&#105;&#108;&#116;&#111;&#58;';

			return '<a href="' . $mailto . $address_encode . '" ' . $_extra . '>' . $text_encode . '</a>';
		} else if ($encode == 'drupal') {
			$address = str_replace('@', '[at]', $address);
			$_text = $text == NULL ? $address : $_text;

			return '<a href="mailto:' . $address . '" ' . $_extra . '>' . $_text . '</a>';
		} else if ($encode == 'texy') {
			$address = str_replace('@', '<!-- ANTISPAM -->&#64;<!-- /ANTISPAM -->', $address);
			$_text = $text == NULL ? $address : $_text;

			return '<a href="mailto:' . $address . '" ' . $_extra . '>' . $_text . '</a>';
		} else {
			// no encoding
			return '<a href="mailto:' . $address . '" ' . $_extra . '>' . $_text . '</a>';
		}
	}

}
