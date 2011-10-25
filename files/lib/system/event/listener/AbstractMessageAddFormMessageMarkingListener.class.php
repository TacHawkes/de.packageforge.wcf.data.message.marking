<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/message/marking/MessageMarking.class.php');

/**
 * Saves the option value whether or not to mark a message
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.message.marking
 * @subpackage  system.event.listener
 * @category    Community Framework
 */
abstract class AbstractMessageAddFormMessageMarkingListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (MODULE_DISPLAY_MESSAGE_MARKINGS) {
			$availableMarkings = MessageMarking::getAvailableMarkings();
			
			switch ($eventName) {
				case 'assignVariables' :
					WCF::getTPL()->assign(array(
						'availableMarkings' => $availableMarkings,
						'markingID' => WCF::getUser()->defaultMessageMarkingID
					));
					WCF::getTPL()->append('additionalSettings', WCF::getTPL()->fetch('messageMarkingSetting'));
					break;
				case 'saved' :
					$markingID = intval($_POST['markingID']);
					if (isset($availableMarkings[$markingID])) {
						$this->saveMessageObjectSetting($eventObj, $className, $markAsTeamMessage);
					}
					break;
			}
		}
	}

	/**
	 * Saves the message setting
	 *
	 * @param 	mixed 		$eventObj
	 * @param	string		$className
	 * @param 	integer 	$markAsTeamMessage
	 */
	abstract public function saveMessageObjectSetting($eventObj, $className, $markAsTeamMessage);
}
