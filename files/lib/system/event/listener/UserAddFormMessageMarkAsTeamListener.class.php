<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/acp/form/UserEditForm.class.php');
require_once(WCF_DIR.'lib/data/user/group/Group.class.php');

/**
 * Adds the user online marking select to user add form.
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.markteam
 * @subpackage  system.event.listener
 * @category    Community Framework
 */
class UserAddFormMessageMarkAsTeamListener implements EventListener {
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
			if ($eventObj instanceof UserEditForm) {
				$groupIDs = $eventObj->user->getGroupIDs();
			}
			else {
				$groupIDs = Group::getGroupIdsByType(array(GROUP::EVERYONE, GROUP::USERS));
			}
				
			if ($eventName == 'readFormParameters') {
				if (isset($_POST['markTeamMessageGroupID'])) $this->markTeamMessageGroupID = intval($_POST['markTeamMessageGroupID']);
			}
			else if ($eventName == 'validate') {
				$groupIDs = array_unique(array_merge(Group::getGroupIdsByType(array(GROUP::EVERYONE, GROUP::USERS)), $eventObj->groupIDs));

				if ($this->markTeamMessageGroupID != 0) {
					// try to validate
					$sql = "SELECT		groupID
						FROM		wcf".WCF_N."_group
						WHERE		groupID = ".$this->markTeamMessageGroupID."
						AND		markAsTeam = 1
						AND 		groupID IN (".implode(',', $groupIDs).")";
					$row = WCF::getDB()->getFirstRow($sql);
					if (!isset($row['groupID'])) $this->markTeamMessageGroupID = 0;
				}

				// save group id
				$eventObj->additionalFields['markTeamMessageGroupID'] = $this->markTeamMessageGroupID;
			}
			else if ($eventName == 'assignVariables') {
				if (!count($_POST) && $eventObj instanceof UserEditForm) {
					// get current values
					$this->markTeamMessageGroupID = $eventObj->user->markTeamMessageGroupID;
				}

				$fields = array();

				$markings = array();
				$sql = "SELECT		groupID, groupName, markAsTeamCss
					FROM		wcf".WCF_N."_group
					WHERE		groupID IN (".implode(',', $groupIDs).")
					AND		markAsTeam = 1
					ORDER BY	groupID ASC";
				$result = WCF::getDB()->sendQuery($sql);
				while ($row = WCF::getDB()->fetchArray($result)) {
					$markings[] = $row;
				}

				if (count($markings)) {
					WCF::getTPL()->assign(array(
						'markings' => $markings,
						'markTeamMessageGroupID' => $this->markTeamMessageGroupID
					));
					$fields[] = array(
						'optionName' => 'markTeamMessageGroupID',
						'divClass' => 'formRadio',
	                       			'beforeLabel' => false,
	                       			'isOptionGroup' => true,
	                        		'html' => WCF::getTPL()->fetch('userAddTeamMessageMarkingSelect')
					);
				}
					
				// add fields
				if (count($fields) > 0) {
					foreach ($eventObj->options as $key1 => $category1) {
						if ($category1['categoryName'] == 'profile') {
							foreach ($category1['categories'] as $key2 => $category2) {
								if ($category2['categoryName'] == 'profile.rank') {
									$eventObj->options[$key1]['categories'][$key2]['options'] = array_merge($category2['options'], $fields);
									return;
								}
							}
								
							$eventObj->options[$key1]['categories'][] = array(
								'categoryName' => 'profile.rank',
								'categoryIconM' => RELATIVE_WCF_DIR . 'icon/userProfileRankM.png',
								'options' => $fields
							);
						}
					}
				}
			}
		}
	}
}
