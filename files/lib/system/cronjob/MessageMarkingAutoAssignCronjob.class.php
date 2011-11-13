<?php
// wcf imports
require_once(WCF_DIR.'lib/data/cronjobs/Cronjob.class.php');
require_once(WCF_DIR.'lib/system/session/UserSession.class.php');

/**
 * Automatically assigns user's default markings depending on group settings
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.message.marking
 * @subpackage	system.cronjob
 * @category 	Community Framework
 */
class MessageMarkingAutoAssignCronjob implements Cronjob {
	/**
	 * @see Cronjob::execute()
	 */
	public function execute($data) {
		$sql = "SELECT		*
			FROM		wcf".WCF_N."_group
			WHERE		messageMarkingID <> 0
			ORDER BY	messageMarkingPriority DESC";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$userIDArray = array();
			$sql = "SELECT		user.userID,
						GROUP_CONCAT(DISTINCT groups.groupID ORDER BY groups.groupID ASC SEPARATOR ',') AS groupIDs
				FROM		wcf".WCF_N."_user user
				LEFT JOIN 	wcf".WCF_N."_user_to_groups groups 
				ON 		(groups.userID = user.userID)
				WHERE		user.defaultMessageMarkingID = 0
				AND 		user.userID IN (
						SELECT	userID
						FROM	wcf".WCF_N."_user_to_groups
						WHERE	groupID = ".$row['groupID']."
						)
				GROUP BY	user.userID";
			$result2 = WCF::getDB()->sendQuery($sql);
			while ($row2 = WCF::getDB()->fetchArray($result2)) {
				$user = new UserSession(null, $row2);
				// only alter users who cannot change their default message marking
				if (!$user->getPermission('user.profile.rank.canSelectMessageMarking')) {
					$userIDArray[] = $row2['userID'];
				}
			}

			if (count($userIDArray)) {
				$userIDArray = array_unique($userIDArray);

				// set default message marking
				$sql = "UPDATE		wcf".WCF_N."_user
					SET		defaultMessageMarkingID = ".$row['messageMarkingID']."
					WHERE		userID IN (".implode(',', $userIDArray).")";
				WCF::getDB()->sendQuery($sql);

				// reset sesions
				Session::resetSessions($userIDArray);
			}
		}
	}
}