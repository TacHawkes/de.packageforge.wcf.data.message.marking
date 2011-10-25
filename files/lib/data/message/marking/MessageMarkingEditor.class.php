<?php
// wcf imports
require_once(WCF_DIR.'lib/data/message/marking/MessageMarking.class.php');

/**
 * Provides functions to manage message markings.
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.message.marking
 * @subpackage  data.message.marking
 * @category    Community Framework
 */
class MessageMarkingEditor extends MessageMarking {
	/**
	 * Creates a new marking.
	 *
	 * @param	string		$title
	 * @param	string		$css
	 * @param	array<integer>	$groupIDs
	 * @param	integer		$disabled
	 * @return	MessageMarkingEditor
	 */
	public static function create($title, $css, $groupIDs, $disabled = 0) {
		$sql = "INSERT INTO	wcf".WCF_N."_message_marking
					(title, css, disabled)
			VALUES		('".escapeString($title)."',
					 '".escapeString($css)."', 
					 ".$disabled.")";
		WCF::getDB()->sendQuery($sql);				

		// get new id and object
		$markingID = WCF::getDB()->getInsertID("wcf".WCF_N."_message_marking", 'markingID');
		$marking = new MessageMarkingEditor($markingID);
		
		// assign to groups
		$marking->assignToGroups($groupIDs);
		
		return $marking;	
	}

	/**
	 * Updates this marking.
	 *
	 * @param	string		$title
	 * @param	string		$css
	 * @param	array<integer>	$groupIDs
	 * @param	integer		$disabled
	 */
	public function update($title, $css, $groupIDs, $disabled = 0) {
		$sql = "UPDATE	wcf".WCF_N."_message_marking
			SET	title = '".escapeString($title)."',
				css = '".escapeString($ratingCategoryID)."',
				disabled = ".$disabled."
			WHERE	markingID = ".$this->markingID;
		WCF::getDB()->sendQuery($sql);
		
		$this->assignToGroups($groupIDs);
	}	
	
	/**
	 * Assigns this marking to the given array of groupIDs
	 *
	 * @param	array		$groupIDs
	 */
	public function assignToGroups($groupIDs) {
		if (!is_array($groupIDs) || !count($groupIDs)) return;

		// delete old assignments
		$sql = "DELETE FROM	wcf".WCF_N."_message_marking_to_group
			WHERE		markingID = ".$this->markingID;
		WCF::getDB()->sendQuery($sql);

		$inserts = "";
		foreach ($groupIDs as $groupID) {
			if (!empty($inserts)) $inserts .= ", ";
			$inserts .= "(".$this->markingID.", ".$groupID.")";
		}

		$sql = "INSERT INTO	wcf".WCF_N."_message_marking_to_group
					(markingID, groupID)
			VALUES		".$inserts;
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Deletes this marking.
	 */
	public function delete() {
		// delete group assignments
		$sql = "DELETE FROM	wcf".WCF_N."_message_marking_to_group
			WHERE		markingID = ".$this->markingID;
		WCF::getDB()->sendQuery($sql);

		// delete marking
		$sql = "DELETE FROM	wcf".WCF_N."_message_marking
			WHERE		markingID = ".$this->markingID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Enables this marking.
	 */
	public function enable() {
		// enable 
		$sql = "UPDATE		wcf".WCF_N."_message_marking
			SET		disabled = 0
			WHERE		markingID = ".$this->markingID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Disables this marking.
	 */
	public function disable() {
		// enable 
		$sql = "UPDATE		wcf".WCF_N."_message_marking
			SET		disabled = 1
			WHERE		markingID = ".$this->markingID;
		WCF::getDB()->sendQuery($sql);
	}
}
?>