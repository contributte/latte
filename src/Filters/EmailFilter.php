<?php declare(strict_types = 1);

namespace Contributte\Latte\Filters;

use Nette\Utils\Html;

class EmailFilter
{

	// Encoding types
	public const
		ENCODE_JAVASCRIPT = 'javascript',
		ENCODE_JAVASCRIPT_CHARCODE = 'javascript_charcode',
		ENCODE_HEX = 'hex',
		ENCODE_DRUPAL = 'drupal',
		ENCODE_TEXY = 'texy';

	public static function filter(string $address, ?string $encode = null, ?string $text = null): Html
	{
		return Html::el()->setHtml(self::protect($address, $encode, $text));
	}

	/**
	 * Smarty {mailto} function plugin
	 *
	 * @link http://www.smarty.net/manual/en/language.function.mailto.php {mailto}
	 */
	public static function protect(string $address, ?string $encode = null, ?string $text = null): string
	{
		$_text = $text === null ? $address : $text;
		$_extra = null;
		if ($encode === 'javascript') {
			$string = 'document.write(\'<a href="mailto:' . $address . '" ' . $_extra . '>' . $_text . '</a>\');';
			$js_encode = '';
			for ($x = 0, $_length = strlen($string); $x < $_length; $x++) {
				$js_encode .= '%' . bin2hex($string[$x]);
			}

			return '<script type="text/javascript">eval(unescape(\'' . $js_encode . '\'))</script>';
		} elseif ($encode === 'javascript_charcode') {
			$string = '<a href="mailto:' . $address . '" ' . $_extra . '>' . $_text . '</a>';
			$ord = [];
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
		} elseif ($encode === 'hex') {
			preg_match('!^(.*)(\?.*)$!', $address, $match);
			if (!empty($match[2])) {
				trigger_error('mailto: hex encoding does not work with extra attributes. Try javascript.', E_USER_WARNING);

				return '';
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
		} elseif ($encode === 'drupal') {
			$address = str_replace('@', '[at]', $address);
			$_text = $text === null ? $address : $_text;

			return '<a href="mailto:' . $address . '" ' . $_extra . '>' . $_text . '</a>';
		} elseif ($encode === 'texy') {
			$address = str_replace('@', '<!-- ANTISPAM -->&#64;<!-- /ANTISPAM -->', $address);
			$_text = $text === null ? $address : $_text;

			return '<a href="mailto:' . $address . '" ' . $_extra . '>' . $_text . '</a>';
		} else {
			// no encoding
			return '<a href="mailto:' . $address . '" ' . $_extra . '>' . $_text . '</a>';
		}
	}

}
