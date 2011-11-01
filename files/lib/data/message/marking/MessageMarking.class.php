<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a message marking.
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.message.marking
 * @subpackage  data.message.marking
 * @category    Community Framework
 */
class MessageMarking extends DatabaseObject {
	/**
	 * stores the markings to groups
	 *
	 * @var array<MessageMarking>
	 */
	protected static $markingsToGroups = array();

	/**
	 * stores the markings
	 *
	 * @var array<MessageMarking>
	 */
	protected static $markings = array();

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
	public function getCSSOutput($targetSelectors = array()) {
		if (!count($targetSelectors)) return $this->css;

		return MessageMarkingUtil::parseCSS($this->css, $targetSelectors);
	}

	/**
	 * Returns the cached markings
	 *
	 * @param	$disabled 	whether or not to return disabled markings
	 * @return 	array<MessageMarking>
	 */
	public static function getCachedMarkings($disabled = false) {
		if (!isset(self::$markings[$disabled])) {
			self::$markings[$disabled] = array();
			WCF::getCache()->addResource(
				'messageMarkings',
			WCF_DIR.'cache/cache.messageMarkings.php',
			WCF_DIR.'lib/system/cache/CacheBuilderMessageMarkings.class.php'
			);

			$data = WCF::getCache()->get('messageMarkings');
			
			foreach ($data as $row) {
				if ($row['disabled'] && !$disabled) continue;
				self::$markings[$disabled][$row['markingID']] = new MessageMarking(null, $row);
			}						
		}

		return self::$markings[$disabled];
	}

	/**
	 * Returns the available markings for the given set
	 * of groupIDs
	 *
	 * @param	$groupIDs		array<integer>
	 * @param	$addDefaultGroups	boolean
	 * @return 	array<MessageMarking>
	 */
	public static function getAvailableMarkings($groupIDs = array(), $addDefaultGroups = true) {
		if (!count($groupIDs)) {
			$groupIDs = WCF::getUser()->getGroupIDs();
		}
		
		// ensure everyone and users group id is included
		if ($addDefaultGroups) {
			$groupIDs = array_unique(array_merge(Group::getGroupIdsByType(array(GROUP::EVERYONE, GROUP::USERS)), $groupIDs));
		}

		$h = StringUtil::getHash(implode(',', $groupIDs));
		if (!isset(self::$markingsToGroups[$h])) {
			$allMarkings = self::getCachedMarkings();
			foreach ($allMarkings as $markingID => $marking) {
				$neededGroupIDs = explode(',', $marking->groupIDs);
				if (!count(array_intersect($groupIDs, $neededGroupIDs))) {
					unset($allMarkings[$markingID]);
				}
			}
				
			self::$markingsToGroups[$h] = $allMarkings;
		}

		return self::$markingsToGroups[$h];
	}
}
