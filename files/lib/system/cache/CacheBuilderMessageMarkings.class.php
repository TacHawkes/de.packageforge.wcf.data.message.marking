<?php
// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 * Caches the message markings.
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.message.marking
 * @subpackage	system.cache
 * @category 	Community Framework
 */
class CacheBuilderMessageMarkings implements CacheBuilder {
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {		
		$data = array();

		// get all markings
		$sql = "SELECT		message_marking.*,
					GROUP_CONCAT(DISTINCT groups.groupID ORDER BY groups.groupID ASC SEPARATOR ',') AS groupIDs
			FROM		wcf".WCF_N."_message_marking message_marking
			LEFT JOIN	wcf".WCF_N."_message_marking_to_group groups
			ON		(groups.markingID = message_marking.markingID)			
			GROUP BY	message_marking.markingID
			ORDER BY	title ASC";
		$result = WCF::getDB()->sendQuery($sql);

		while ($row = WCF::getDB()->fetchArray($result)) {
			$data[$row['markingID']] = $row;
		}

		return $data;
	}
}
