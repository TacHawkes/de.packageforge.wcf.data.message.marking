<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/message/sidebar/MessageSidebarFactory.class.php');
require_once(WCF_DIR.'lib/data/user/teamMarkings/DummySidebarObject.class.php');

/**
 * Adds the team marking select to the user profile edit form.
 * 
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.markteam
 * @subpackage  system.event.listener
 * @category    Community Framework
 */
class UserProfileEditFormMessageMarkAsTeamListener implements EventListener {
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
			if ($eventObj->activeCategory == 'profile') {
				if ($eventName == 'validate') {
					// TODO: Remove debug
					//if (WCF::getUser()->getPermission('user.profile.rank.canSelectTeamMessageMarking')) {
						if (isset($_POST['markTeamMessageGroupID'])) $this->markTeamMessageGroupID = intval($_POST['markTeamMessageGroupID']);
						
						// validate mark team message group id
						if ($this->markTeamMessageGroupID) {
							try {
								$sql = "SELECT		groupID
									FROM		wcf".WCF_N."_group
									WHERE		groupID = ".$this->markTeamMessageGroupID."
									AND 		groupID IN (".implode(',', WCF::getUser()->getGroupIDs()).")";
								$row = WCF::getDB()->getFirstRow($sql);
								if (!isset($row['groupID'])) throw new UserInputException('markTeamMessageGroupID');
								
								// save groupID
								$eventObj->additionalFields['markTeamMessageGroupID'] = $this->markTeamMessageGroupID;
							}
							catch (UserInputException $e) {
								$eventObj->errorType[$e->getField()] = $e->getType();
							}
						}
						else if ($this->markTeamMessageGroupID == 0) {
							// save groupID
							$eventObj->additionalFields['markTeamMessageGroupID'] = 0;
						}
					// }
				}
				else if ($eventName == 'assignVariables') {
					if (!count($_POST)) {
						// get current value
						$this->markTeamMessageGroupID = WCF::getUser()->markTeamMessageGroupID;
					}
					
					$fields = array();
					
					// get team message markings
					// TODO: remove debug!
					// if (WCF::getUser()->getPermission('user.profile.rank.canSelectTeamMessageMarking')) {
						$markings = array();
						$sql = "SELECT		groupID, groupName, markAsTeamCss
							FROM		wcf".WCF_N."_group
							WHERE		groupID IN (".implode(',', WCF::getUser()->getGroupIDs()).")
							AND		markAsTeam = 1
							ORDER BY	groupID ASC";
						$result = WCF::getDB()->sendQuery($sql);
						while ($row = WCF::getDB()->fetchArray($result)) {
							$row['parsedCSS'] = TeamMarkingsUtil::parseCSS($row['markAsTeamCss'], array('#teamMarkingPreview'.$row['groupID']));							
							$markings[] = $row;
						}
						
						if (count($markings)) {
							$sidebarFactory = new MessageSidebarFactory($this);
							$sidebarFactory->create(new DummySidebarObject());
							$sidebarFactory->init();
							
							$additionalCSS = '';
							foreach ($markings as $marking) {
								$additionalCSS .= $marking['parsedCSS'];
							}
							WCF::getTPL()->append('specialStyles', '<style type="text/css">'.$additionalCSS.'</style>');
							
							WCF::getTPL()->assign(array(
								'markings' => $markings,
								'markTeamMessageGroupID' => $this->markTeamMessageGroupID,
								'sidebarFactory' => $sidebarFactory
							));
							$fields[] = array(
								'optionName' => 'markTeamMessageGroupID',
								'divClass' => 'formRadio',
			                       			'beforeLabel' => false,
			                       			'isOptionGroup' => true,
			                        		'html' => WCF::getTPL()->fetch('userProfileEditTeamMessageMarkingSelect')
			                        	);
						}
					//}
				
					// add fields
					if (count($fields) > 0) {
						foreach ($eventObj->options as $key => $category) {
							if ($category['categoryName'] == 'profile.rank') {
								$eventObj->options[$key]['options'] = array_merge($category['options'], $fields);
								return;
							}
						}
						
						$eventObj->options[] = array(
							'categoryName' => 'profile.rank',
							'categoryIconM' => '',
							'options' => $fields
						);
					}
				}
			}
		}
	}
}
?>