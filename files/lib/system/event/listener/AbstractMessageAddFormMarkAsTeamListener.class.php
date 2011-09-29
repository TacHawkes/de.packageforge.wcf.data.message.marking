<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Saves the option value whether or not to mark a team message
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.markteam
 * @subpackage  system.event.listener
 * @category    Community Framework
 */
abstract class AbstractMessageAddFormMarkAsTeamListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (!MODULE_USER_MARK_TEAM_MESSAGE || !in_array(WCF::getUser()->markTeamMessageGroupID, WCF::getUser()->getGroupIDs())) return;
		
		switch ($eventName) {
			case 'assignVariables' :
				WCF::getTPL()->append('additionalSettings', WCF::getTPL()->fetch('markAsTeamSetting'));
				break;
			case 'saved' :
				$markAsTeamMessage = isset($_POST['markAsTeamMessage']) ? 1 : 0;
				$this->saveMessageObjectSetting($eventObj, $className, $markAsTeamMessage);
				
				$options = array('markAsTeamMessage' => $markAsTeamMessage);		
				$editor = WCF::getUser()->getEditor();
				$editor->updateOptions($options);
				break;
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
?>