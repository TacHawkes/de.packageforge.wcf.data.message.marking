<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/message/marking/MessageMarking.class.php');

/**
 * Marks messages
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.message.marking
 * @subpackage  system.event.listener
 * @category    Community Framework
 */
abstract class AbstractMessageMarkingListener implements EventListener {	
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (MODULE_DISPLAY_MESSAGE_MARKINGS == 1) {			
			if ($eventName == 'readData') {
				$this->appendMessageObjectList($eventObj, $className, $eventName);
			}
			else if ($eventName == 'assignVariables') {
				$messageToMarkings = array();
				foreach ($this->getMessageObjects($eventObj, $className, $eventName) as $message) {
					$markings = MessageMarking::getAvailableMarkings(explode(',', $message->groupIDs));
					if ($message->userID 
						&& $message->markingID != 0 
						&& isset($markings[$message->markingID])) {
						if (!isset($messageToMarkings[$message->markingID])) $messageToMarkings[$message->markingID] = array();
						$messageToMarkings[$message->markingID][] = $this->getObjectID($message);
					}
				}

				// append the special styles
				if (count($messageToMarkings)) {
					$additionalCSS = '';
					$allMarkings = MessageMarking::getCachedMarkings();
					foreach ($messageToMarkings as $markingID => $objectIDs) {
						if (isset($allMarkings[$markingID])) {
							$marking = $allMarkings[$markingID];

							// build selector
							$targetSelectors = array();
							foreach ($objectIDs as $objectID) {
								$targetSelectors[] = $this->getMessageContainerSelector($objectID);
							}

							$additionalCSS .= $marking->getCSSOutput($targetSelectors);
						}
					}

					WCF::getTPL()->append('specialStyles', '<style type="text/css">'.$additionalCSS.'</style>');
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
