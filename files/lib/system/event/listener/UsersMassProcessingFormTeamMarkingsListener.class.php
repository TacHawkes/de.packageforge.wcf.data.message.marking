<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Assigns users a team message marking
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.markteam
 * @subpackage  system.event.listener
 * @category    Community Framework
 */
class UsersMassProcessingFormTeamMarkingsListener implements EventListener {
	/**
	 * group id
	 *
	 * @var integer
	 */
	protected $markTeamMessageGroupID = 0;

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (MODULE_USER_MARK_TEAM_MESSAGE == 1) {
			if ($eventName == 'readParameters') {
				$eventObj->availableActions[] = 'assignTeamMessageMarking';
			}
			else if ($eventName == 'readFormParameters') {
				if (isset($_POST['markTeamMessageGroupID'])) $this->markTeamMessageGroupID = intval($_POST['markTeamMessageGroupID']);
			}
			else if ($eventName == 'validate') {
				if ($eventObj->action == 'assignTeamMessageMarking') {
					if ($this->markTeamMessageGroupID != 0) {			
						$sql = "SELECT		groupID
							FROM		wcf".WCF_N."_group
							WHERE		groupID = ".$this->markTeamMessageGroupID."
							AND 		markAsTeam = 1";
						$row = WCF::getDB()->getFirstRow($sql);
						if (!isset($row['groupID'])) throw new UserInputException('markTeamMessageGroupID');
					}
				}
			}
			else if ($eventName == 'buildConditions') {
				if ($eventObj->action == 'assignTeamMessageMarking') {
					// get user ids
					$userIDArray = array();
					$sql = "SELECT		user.userID
						FROM		wcf".WCF_N."_user user
						".$eventObj->conditions->get();
					$result = WCF::getDB()->sendQuery($sql);
					while ($row = WCF::getDB()->fetchArray($result)) {
						$userIDArray[] = $row['userID'];
					}
					
					if ($this->markTeamMessageGroupID != 0) {
						// filter user ids
						$sql = "SELECT		userID
							FROM		wcf".WCF_N."_user_to_groups
							WHERE		groupID = ".$this->markTeamMessageGroupID."
							AND		userID IN (".implode(',', $userIDArray).")";
						$result = WCF::getDB()->sendQuery($sql);
						$userIDArray = array(); 
						while ($row = WCF::getDB()->fetchArray($result)) {
							$userIDArray[] = $row['userID'];
						}
					}
						
					if (count($userIDArray)) {
						// save assignment
						$sql = "UPDATE	wcf".WCF_N."_user
							SET	markTeamMessageGroupID = ".$this->markTeamMessageGroupID."
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
				WCF::getTPL()->append('additionalActions', '<li><label><input onclick="if (IS_SAFARI) enableAssignTeamMessageMarking()" onfocus="enableAssignTeamMessageMarking()" type="radio" name="action" value="assignTeamMessageMarking" '.($eventObj->action == 'assignTeamMessageMarking' ? 'checked="checked" ' : '').'/> '.WCF::getLanguage()->get('wcf.acp.user.assignTeamMessageMarking').'</label></li>');
				
				// read markings
				$markings = array();
				$sql = "SELECT		groupID, groupName, markAsTeamCss
					FROM		wcf".WCF_N."_group
					WHERE		markAsTeam = 1
					ORDER BY	groupID ASC";
				$result = WCF::getDB()->sendQuery($sql);
				while ($row = WCF::getDB()->fetchArray($result)) {					
					$markings[] = $row;
				}
				
				WCF::getTPL()->assign(array(
					'markings' => $markings,
					'markTeamMessageGroupID' => $this->markTeamMessageGroupID,
					'errorField' => $eventObj->errorField,
					'errorType' => $eventObj->errorType
				));
				WCF::getTPL()->append('additionalActionSettings', WCF::getTPL()->fetch('usersMassProcessingAssignTeamMessageMarking'));
			}
		}
	}
}
?>