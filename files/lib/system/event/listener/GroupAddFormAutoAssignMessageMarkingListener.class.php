<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/message/marking/MessageMarking.class.php');

/**
 * Adds the selection for an automatic assignment of a default message marking for group members
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.message.marking
 * @subpackage  system.event.listener
 * @category    Community Framework
 */
class GroupAddFormAutoAssignMessageMarkingListener implements EventListener {
	/**
	 * marking id
	 *
	 * @var integer
	 */
	protected $markingID = 0;
	
	/**
	 * available message markings
	 * 	 
	 * @var array<MessageMarking>
	 */
	protected $availableMarkings = array();
	
	/**
	 * Constructs a new GroupAddFormAutoAssignMessageMarkingListener object.	 
	 */
	public function __construct() {
		$this->availableMarkings = MessageMarking::getCachedMarkings();
	}

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (MODULE_DISPLAY_MESSAGE_MARKINGS == 1) {			
			if ($eventName == 'readFormParameters') {
				if (isset($_POST['markingID'])) $this->markingID = intval($_POST['markingID']);
			}
			else if ($eventName == 'validate') {				
				if (!isset($this->availableMarkings[$this->markingID])) $this->markingID = 0;
				
				// TODO: Validate if the option user.profile.rank.canSelectMessageMarking
				// is turned off. Otherwise throw a user input exception.
							
				// save group id
				$eventObj->additionalFields['defaultMessageMarkingID'] = $this->markingID;
			}
			else if ($eventName == 'saved') {
				if ($this->markingID != 0) {
					// if the selected message marking is not available for this group
					// add this group id to the list of enabled groups
					$marking = $this->availableMarkings[$this->markingID];
					$groupIDs = explode(',', $marking->groupIDs);
					if (!in_array($eventObj->group->groupID, $groupIDs)) {
						$groupIDs[] = $eventObj->group->groupID;
						$markingEditor = $marking->getEditor();
						$markingEditor->assignToGroups($groupIDs);

						// clear cache
						WCF::getCache()->clear(WCF_DIR.'cache/', 'cache.messageMarkings.php');
					}
				}
			}
			else if ($eventName == 'assignVariables') {
				if (!count($_POST) && $eventObj instanceof UserEditForm) {
					// get current values
					$this->markingID = $eventObj->group->messageMarkingID;
				}

				// TODO: Implement this
			}
		}
	}
}
