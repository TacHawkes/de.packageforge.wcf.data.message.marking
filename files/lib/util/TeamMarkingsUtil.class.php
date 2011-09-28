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
		
		// extract selectors and content
		if (preg_match_all(self::$cssSplitRegEx, $css, $matches, PREG_SET_ORDER)) {
			foreach ($matches as $match) {
				$selector = $match[1];
				$content = $match[2];
				
				// append the target selector
				// e.g.: .foo { ... } becomes #targetA .foo, #targetB .foo { ... }
				$newSelector = '';
				foreach ($targetSelectors as $targetSelector) {
					if (!empty($newSelector)) $newSelector .= ', ';
					$newSelector .= $targetSelector.' '.$selector;
				}
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
}