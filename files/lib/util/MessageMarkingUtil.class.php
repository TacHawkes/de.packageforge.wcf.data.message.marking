<?php
/**
 * Message markings utility
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.message.marking
 * @subpackage  util
 * @category    Community Framework
 */
class MessageMarkingUtil {
	/**
	 * Regular expression for splitting the css into selector and content parts
	 */
	const CSS_SPLIT_REG_EX = '/((?:(?:[^,{]+),?)*?)\{([^}]*)\}/is';
	
	/**
	 * ActiveStyle object
	 *
	 * @var ActiveStyle
	 */
	protected static $style = null;
	
	/**
	 * Special selectors which require different handling
	 * 	 
	 * @var array<string>
	 */
	protected static $specialSelectors = array(
		// wcf
		'.disabled',
		'.deleted',
		'.marked',
		'.message',
		// wbb
		'.threadStarterPost'
	);

	/**
	 * Parses the css and inserts the target selectors.
	 *
	 * @param 	string 		$css
	 * @param	array<string>	$targetSelectors
	 * @return	string
	 */
	public static function parseCSS($css, $targetSelectors) {
		$newCss = '';
		$css = StringUtil::unifyNewlines($css);
		// extract selectors and content
		if (preg_match_all(self::CSS_SPLIT_REG_EX, $css, $matches, PREG_SET_ORDER)) {
			foreach ($matches as $match) {
				$selector = ltrim($match[1]);
				$content = $match[2];

				// get new selector
				$newSelector = self::appendSelector($selector, $targetSelectors);
				// insert content
				$newSelector .= ' { '.self::parseStyleVariables($content).' }';
				// insert new lines
				$newSelector .= "\n\n";

				// append css
				$newCss .= $newSelector;
			}
		}

		return $newCss;
	}

	/**
	 * Append the given selector with the target selectors
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
			if (in_array(StringUtil::trim($selector), self::$specialSelectors)) {
				$connector = '';
			}
			else {
				$connector = ' ';	
			}
			foreach ($targetSelectors as $targetSelector) {
				if (!empty($newSelector)) $newSelector .= ', ';
				$newSelector .= $targetSelector.$connector.$selector;
			}
		}

		return $newSelector;
	}

	/**
	 * Parses style variables in content css
	 *
	 * @param string $content
	 */
	protected static function parseStyleVariables($content) {
		self::$style = StyleManager::getStyle();

		$content = preg_replace_callback('/\$([a-z0-9_\-\.]+)\$/', array('self', 'parseStyleVariablesCallback'), $content);

		return $content;
	}

	/**
	 * Callback for parser
	 *
	 * @param array $match
	 */
	protected static function parseStyleVariablesCallback($match) {
		if (self::$style->getVariable($match[1])) {
			$value = self::$style->getVariable($match[1]);
			// fix images location
			if ($match[1] == 'global.images.location' && !FileUtil::isURL($value) && substr($value, 0, 1) != '/') {
				$value = '../'.$value;
			}
			// fix icons location
			if ($match[1] == 'global.icons.location' && !FileUtil::isURL($value) && substr($value, 0, 1) != '/') {
				$value = '../'.$value;
			}
			return $value;
		}
		else {
			return $match[0];
		}
	}
}
