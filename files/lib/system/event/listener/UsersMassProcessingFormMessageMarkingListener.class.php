<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/message/marking/MessageMarking.class.php');

/**
 * Assigns users a message marking
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.message.marking
 * @subpackage  system.event.listener
 * @category    Community Framework
 */
class UsersMassProcessingFormMessageMarkingListener implements EventListener {
	/**
	 * marking id
	 *
	 * @var integer
	 */
	protected $markingID = 0;

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (MODULE_DISPLAY_MESSAGE_MARKINGS == 1) {
			if ($eventName == 'readParameters') {
				$eventObj->availableActions[] = 'assignDefaultMessageMarking';
			}
			else if ($eventName == 'readFormParameters') {
				if (isset($_POST['markingID'])) $this->markingID = intval($_POST['markingID']);
			}
			else if ($eventName == 'validate') {
				if ($eventObj->action == 'assignDefaultMessageMarking') {					
					if ($this->markingID != 0) {
						$allMarkings = MessageMarking::getCachedMarkings();						
						if (!isset($allMarkings[$this->markingID])) throw new UserInputException('markingID');
					}
				}
			}
			else if ($eventName == 'buildConditions') {
				if ($eventObj->action == 'assignDefaultMessageMarking') {
					// get users
					$users = array();
					$sql = "SELECT		user.userID,
								GROUP_CONCAT(DISTINCT user_to_groups.groupID ORDER BY user_to_groups.groupID ASC SEPARATOR ',') AS groupIDs					
						FROM		wcf".WCF_N."_user user
						LEFT JOIN	wcf".WCF_N."_user_to_groups user_to_groups
						ON		(user_to_groups.userID = user.userID)
						".$eventObj->conditions->get()."
						GROUP BY	user.userID";
					$result = WCF::getDB()->sendQuery($sql);
					while ($row = WCF::getDB()->fetchArray($result)) {
						$users[$row['userID']] = $row;
					}
					
					// if id != 0 check if id is available for each user
					if ($this->markingID != 0) {
						foreach ($users as $key => $user) {
							if (!count(MessageMarking::getAvailableMarkings(explode(',', $user['groupIDs'])))) {
								unset($users[$key]);	
							}
						}
					}
					$userIDArray = array_keys($users);
					unset ($users);					

					if (count($userIDArray)) {
						// save assignment
						$sql = "UPDATE	wcf".WCF_N."_user
							SET	defaultMessageMarkingID = ".$this->markingID."
							WHERE	userID IN (".implode(',', $userIDArray).")";
						WCF::getDB()->sendQuery($sql);

						// reset sessions
						Session::resetSessions($userIDArray, true, false);

						// set affected users
						$eventObj->affectedUsers = count($userIDArray);
					}
				}
			}
			else if ($eventName == 'assignVariables') {
				WCF::getTPL()->append('additionalActions', '<li><label><input onclick="if (IS_SAFARI) enableAssignDefaultMessageMarking()" onfocus="enableAssignDefaultMessageMarking()" type="radio" name="action" value="assignDefaultMessageMarking" '.($eventObj->action == 'assignDefaultMessageMarking' ? 'checked="checked" ' : '').'/> '.WCF::getLanguage()->get('wcf.acp.user.assignDefaultMessageMarking').'</label></li>');

				// read and assign markings
				WCF::getTPL()->assign(array(
					'markings' => MessageMarking::getCachedMarkings(),
					'markingID' => $this->markingID,
					'errorField' => $eventObj->errorField,
					'errorType' => $eventObj->errorType
				));
				WCF::getTPL()->append('additionalActionSettings', WCF::getTPL()->fetch('usersMassProcessingAssignDefaultMessageMarking'));
			}
		}
	}
}
