<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/user/group/Group.class.php');

/**
 * Marks team member's messages
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.markteam
 * @subpackage  system.event.listener
 * @category    Community Framework
 */
abstract class AbstractMarkTeamMessageListener implements EventListener {
	/**
	 * mark group ids
	 *
	 * @var array<integer>
	 */
	protected $groupIDs = array();

	/**
	 * mark groups
	 *
	 * @var array<Group>
	 */
	protected $groups = array();

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (MODULE_USER_MARK_TEAM_MESSAGE == 1) {
			$this->loadTeamMessageGroups();
			if ($eventName == 'readData') {
				$this->appendMessageObjectList($eventObj, $className, $eventName);
			}
			else if ($eventName == 'assignVariables') {
				$messageToGroups = array();
				foreach ($this->getMessageObjects($eventObj, $className, $eventName) as $message) {
					// check marking requirements and flag message for marking
					if (in_array($message->markTeamMessageGroupID, explode(',', $message->groupIDs))
					&& $message->markAsTeamMessage
					&& $message->userID) {
						if (!isset($messageToGroups[$message->markTeamMessageGroupID])) $messageToGroups[$message->markTeamMessageGroupID] = array();
						$messageToGroups[$message->markTeamMessageGroupID][] = $this->getObjectID($message);
					}
				}

				// append the special styles
				if (count($messageToGroups)) {
					$additionalCSS = '';
					foreach ($messageToGroups as $groupID => $objectIDs) {
						if (isset($this->groups[$groupID])) {
							$group = $this->groups[$groupID];

							// build selector
							$targetSelectors = array();
							foreach ($objectIDs as $objectID) {
								$targetSelectors[] = $this->getMessageContainerSelector($objectID);
							}

							$additionalCSS .= TeamMarkingsUtil::parseCSS($group->markAsTeamCSS, $targetSelectors);
						}
					}

					WCF::getTPL()->append('specialStyles', '<style type="text/css">'.$additionalCSS.'</style>');
				}
			}
		}
	}

	/**
	 * Loads all groups with team marking css from cache
	 */
	protected function loadTeamMessageGroups() {
		if (!count($this->groups)) {
			// load all groups
			WCF::getCache()->addResource('groups', WCF_DIR.'cache/cache.groups.php', WCF_DIR.'lib/system/cache/CacheBuilderGroups.class.php');
			$cache = WCF::getCache()->get('groups');
			$groups = $cache['groups'];

			// filter
			foreach ($groups as $group) {
				if ($group['markAsTeam']) {
					$this->groupIDs[] = $group['groupID'];
					$this->groups[$group['groupID']] = new Group(null, $group);
				}
			}
		}
	}

	/**
	 * Appends the message object list with the necessary fields
	 *
	 * @param 	mixed 		$eventObj
	 * @param 	string 		$className
	 * @param 	string 		$eventName
	 */
	abstract public function appendMessageObjectList($eventObj, $className, $eventName);

	/**
	 * Returns the message objects
	 *
	 * @param 	mixed 		$eventObj
	 * @param 	string 		$className
	 * @param 	string 		$eventName
	 */
	abstract public function getMessageObjects($eventObj, $className, $eventName);

	/**
	 * Returns the object's ID
	 *
	 * @param 	mixed 		$object
	 */
	abstract public function getObjectID($object);

	/**
	 * Returns the object's css selector
	 *
	 * @param 	integer 	$objectID
	 */
	abstract public function getMessageContainerSelector($objectID);
}
?>