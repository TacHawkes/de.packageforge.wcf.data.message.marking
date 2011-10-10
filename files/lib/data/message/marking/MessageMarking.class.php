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
			$sql = "SELECT		message_marking.*
				FROM 		wcf".WCF_N."_message_marking message_marking
				WHERE 		message_marking.markingID = ".$markingID;
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
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
}
?>