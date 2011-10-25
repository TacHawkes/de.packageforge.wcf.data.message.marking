<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a message marking.
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.markteam
 * @subpackage  data.message.marking
 * @category    Community Framework
 */
class MessageMarking extends DatabaseObject {	
	/**
	 * Creates a new MessageMarking object.
	 *
	 * @param	integer		$markingID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($markingID, $row = null) {
		if ($markingID !== null) {
			$sql = "SELECT		message_marking.*,
						GROUP_CONCAT(DISTINCT groups.groupID ORDER BY groups.groupID ASC SEPARATOR ',') AS groupIDs
				FROM 		wcf".WCF_N."_message_marking message_marking
				LEFT JOIN	wcf".WCF_N."_message_marking_to_group groups
				ON		(groups.markingID = message_marking.markingID)
				WHERE 		message_marking.markingID = ".$markingID."
				GROUP BY	message_marking.markingID";
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}
	
	/**
	 * Returns the parsed css output of this marking
	 * 
	 * @param 	array<string>	$targetSelectors
	 * @return	string
	 */
	public function getCSSOutput($targetSelector = array()) {
		if (empty($targetSelector)) return $this->css;
		
		TeamMarkingsUtil::parseCSS($this->css, $targetSelector);
	}
	
	/**
	 * Returns the cached markings
	 *
	 * @param	$disabled 	whether or not to return disabled markings
	 * @return 	array<MessageMarking>
	 */
	public static function getCachedMarkings($disabled = false) {
		WCF::getCache()->addResource(
			'messageMarkings',
			WCF_DIR.'cache/cache.messageMarkings.php',
			WCF_DIR.'lib/system/cache/CacheBuilderMessageMarkings.class.php'
		);

		$data = WCF::getCache()->get('messageMarkings');
		$markings = array();

		foreach ($data as $row) {
			if ($row['disabled'] && !$disabled) continue;
			$markings[$row['markingID']] = new MessageMarking(null, $row);
		}

		return $markings;
	}
	
	/**
	 * Returns the available markings for the given set
	 * of groupIDs
	 *	 
	 * @param	$groupIDs		array<integer>
	 * @return 	array<MessageMarking>
	 */
	public static function getAvailableMarkings($groupIDs = array()) {
		if (empty($groupIDs)) {
			$groupIDs = WCF::getUser()->getGroupIDs();
		}
		$groupIDs = array_merge(Group::getGroupIdsByType(array(GROUP::EVERYONE, GROUP::USERS)), $groupIDs);
		
		$allMarkings = self::getCachedMarkings();
		foreach ($allMarkings as $markingID => $marking) {
			$neededGroupIDs = explode(',', $marking->groupIDs);
			if (empty(array_intersect($groupIDs, $neededGroupIDs))) {
				unset($allMarkings[$markingID]);
			}
		}
		
		return $allMarkings;
	}
}
?>