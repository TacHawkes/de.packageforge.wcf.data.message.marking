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
					if (WCF::getUser()->getPermission('user.profile.rank.canSelectMessageMarking')) {
						WCF::getTPL()->assign(array(
							'availableMarkings' => $availableMarkings,
							'markingID' => $this->getMarkingID($eventObj, $className)
						));
						WCF::getTPL()->append('additionalInformationFields', WCF::getTPL()->fetch('messageMarkingSetting'));						
					}
					break;
				case 'saved' :
					if (isset($_POST['markingID'])) $markingID = intval($_POST['markingID']);
					else {
						$markingID = WCF::getUser()->defaultMessageMarkingID;
					}					
					if ($markingID == 0 || isset($availableMarkings[$markingID])) {
						$this->saveMessageObjectSetting($eventObj, $className, $markingID);
					}
					break;
			}
		}
	}

	/**
	 * Saves the marking id
	 *
	 * @param 	mixed 		$eventObj
	 * @param	string		$className
	 * @param 	integer 	$markingID
	 */
	abstract public function saveMessageObjectSetting($eventObj, $className, $markingID);
	
	/**
	 * Returns the marking id. Either the user's default id on add processes
	 * or the old if on edit processes.
	 *
	 * @param	mixed		$eventObj
	 * @param	string		$className
	 * @return	integer	 
	 */
	abstract public function getMarkingID($eventObj, $className);
}
