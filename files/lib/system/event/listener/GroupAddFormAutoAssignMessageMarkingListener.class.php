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
	 * marking priority
	 *
	 * @var integer
	 */
	protected $markingPriority = 0;

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
				if (isset($_POST['markingPriority'])) $this->markingPriority = intval($_POST['markingPriority']);
			}
			else if ($eventName == 'validate') {
				if (!isset($this->availableMarkings[$this->markingID])) $this->markingID = 0;

				// validate if the group option is disabled, otherwise throw an exception
				foreach ($eventObj->activeOptions as $option) {
					if ($option['optionName'] == 'user.profile.rank.canSelectMessageMarking'
					&& $option['optionValue'] == 1) {
						throw new UserInputException('markingID', 'optionEnabled');
					}
				}
					
				// save
				$eventObj->additionalFields['messageMarkingID'] = $this->markingID;
				$eventObj->additionalFields['messageMarkingPriority'] = $this->markingPriority;
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
				if (!count($_POST) && $eventObj instanceof GroupEditForm) {
					// get current values
					$this->markingID = $eventObj->group->messageMarkingID;
					$this->markingPriority = $eventObj->group->messageMarkingPriority;
				}

				WCF::getTPL()->assign(array(
					'availableMarkings' => $this->availableMarkings,
					'markingID' => $this->markingID,
					'markingPriority' => $this->markingPriority
				));
				WCF::getTPL()->append('additionalFieldSets', WCF::getTPL()->fetch('groupAddFormAutoAssignMessageMarking'));
			}
		}
	}
}
