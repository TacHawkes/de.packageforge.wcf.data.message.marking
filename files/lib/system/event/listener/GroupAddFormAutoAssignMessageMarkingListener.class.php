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
				if ($this->markingID > 0) {
					if (!isset($this->availableMarkings[$this->markingID])) $this->markingID = 0;

					try {
						// validate if the group option is disabled, otherwise throw an exception
						if (isset($eventObj->values['user.profile.rank.canSelectMessageMarking'])) {
							throw new UserInputException('markingID', 'optionEnabled');
						}
					}
					catch (UserInputException $e) {
						$eventObj->errorType[$e->getField()] = $e->getType();
					}

					if (count($eventObj->errorType) > 0) {
						throw new UserInputException('markingID', $eventObj->errorType);
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
						
					// execute cronjob
					require_once(WCF_DIR.'lib/data/cronjobs/CronjobEditor.class.php');
					$sql = "SELECT		cronjob.*, package.packageDir
						FROM		wcf".WCF_N."_cronjobs cronjob
						LEFT JOIN	wcf".WCF_N."_package package
						ON		(package.packageID = cronjob.packageID)
						WHERE		cronjob.classPath = 'lib/system/cronjob/MessageMarkingAutoAssignCronjob.class.php'";
					$cronjob = new CronjobEditor(null, WCF::getDB()->getFirstRow($sql));
					$cronjob->execute();
				}
			}
			else if ($eventName == 'assignVariables') {
				if (!count($_POST) && $eventObj instanceof GroupEditForm) {
					// get current values
					$this->markingID = $eventObj->group->messageMarkingID;
					$this->markingPriority = $eventObj->group->messageMarkingPriority;
				}

				WCF::getTPL()->assign(array(
					'markings' => $this->availableMarkings,
					'markingID' => $this->markingID,
					'markingPriority' => $this->markingPriority,
					'errorField' => $eventObj->errorField,
					'errorType' => $eventObj->errorType
				));
				WCF::getTPL()->append('additionalFieldSets', WCF::getTPL()->fetch('groupAddFormAutoAssignMessageMarking'));
			}
		}
	}
}
