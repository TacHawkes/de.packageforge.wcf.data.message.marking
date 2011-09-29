<?php
/**
 * Team markings utility
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.markteam
 * @subpackage  util
 * @category    Community Framework
 */
class TeamMarkingsUtil {
	protected static $cssSplitRegEx = '/((?:(?:[^,{]+),?)*?)\{([^}]*)\}/is';

	/**
	 * Parses the group css and inserts the target selectors.
	 *
	 * @param 	string 		$css
	 * @param	array<string>	$targetSelectors
	 * @return	string
	 */
	public static function parseCSS($css, $targetSelectors) {
		$newCss = '';
		$css = StringUtil::unifyNewlines($css);
		// extract selectors and content
		if (preg_match_all(self::$cssSplitRegEx, $css, $matches, PREG_SET_ORDER)) {
			foreach ($matches as $match) {
				$selector = ltrim($match[1]);
				$content = $match[2];

				// get new selector
				$newSelector = self::appendSelector($selector, $targetSelectors);
				// insert content
				$newSelector .= ' { '.$content.' }';
				// insert new lines
				$newSelector .= "\n\n";

				// append css
				$newCss .= $newSelector;
			}
		}

		return $newCss;
	}

	/**
	 * Append the target selector
	 * e.g.: .foo { ... } becomes #targetA .foo, #targetB .foo { ... }
	 *
	 * @param 	string 		$selector
	 * @param 	array<string> 	$targetSelectors
	 * @return	string
	 */
	protected static function appendSelector($selector, $targetSelectors) {
		$newSelector = '';
		if (StringUtil::indexOf($selector, ',') !== false) {
			$selectors = explode(',', $selector);
			foreach ($selectors as $selectorValue) {
				if (!empty($newSelector)) $newSelector .= ', ';
				$newSelector .= self::appendSelector($selectorValue, $targetSelectors);
			}
		}
		else {
			foreach ($targetSelectors as $targetSelector) {
				if (!empty($newSelector)) $newSelector .= ', ';
				$newSelector .= $targetSelector.' '.$selector;
			}
		}

		return $newSelector;
	}
}