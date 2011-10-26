<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/acp/form/UserEditForm.class.php');
require_once(WCF_DIR.'lib/data/message/marking/MessageMarking.class.php');

/**
 * Adds the default message marking select to user add form.
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.message.marking
 * @subpackage  system.event.listener
 * @category    Community Framework
 */
class UserAddFormMessageMarkingListener implements EventListener {
	/**
	 * message marking id
	 *
	 * @var integer
	 */
	protected $defaultMessageMarkingID = 0;
	
	/**
	 * available message markings
	 * 	 
	 * @var array<MessageMarking>
	 */
	protected $availableMarkings = array();

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (MODULE_DISPLAY_MESSAGE_MARKINGS == 1) {
			if ($eventObj instanceof UserEditForm) {
				$groupIDs = $eventObj->user->getGroupIDs();
				$this->availableMarkings = MessageMarking::getAvailableMarkings($groupIDs);
			}
			else {
				$groupIDs = Group::getGroupIdsByType(array(GROUP::EVERYONE, GROUP::USERS));
				$this->availableMarkings = MessageMarking::getAvailableMarkings($groupIDs);
			}
			
			if ($eventName == 'readFormParameters') {
				if (isset($_POST['defaultMessageMarkingID'])) $this->defaultMessageMarkingID = intval($_POST['defaultMessageMarkingID']);
			}
			else if ($eventName == 'validate') {
				$this->availableMarkings = MessageMarking::getAvailableMarkings($eventObj->groupIDs);
				if (!isset($this->availableMarkings[$this->defaultMessageMarkingID])) $this->defaultMessageMarkingID = 0;
							
				// save group id
				$eventObj->additionalFields['defaultMessageMarkingID'] = $this->defaultMessageMarkingID;
			}
			else if ($eventName == 'assignVariables') {
				if (!count($_POST) && $eventObj instanceof UserEditForm) {
					// get current values
					$this->defaultMessageMarkingID = $eventObj->user->defaultMessageMarkingID;
				}

				$fields = array();
				
				if (count($this->availableMarkings)) {
					WCF::getTPL()->assign(array(
						'markings' => $this->availableMarkings,
						'defaultMessageMarkingID' => $this->defaultMessageMarkingID
					));
					$fields[] = array(
						'optionName' => 'defaultMessageMarkingID',
						'divClass' => 'formRadio',
	                       			'beforeLabel' => false,
	                       			'isOptionGroup' => true,
	                        		'html' => WCF::getTPL()->fetch('userAddMessageMarkingSelect')
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
