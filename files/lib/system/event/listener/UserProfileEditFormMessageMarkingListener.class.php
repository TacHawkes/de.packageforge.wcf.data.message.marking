<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/message/sidebar/MessageSidebarFactory.class.php');
require_once(WCF_DIR.'lib/data/message/marking/MessageMarking.class.php');
require_once(WCF_DIR.'lib/data/message/marking/util/DummySidebarObject.class.php');

/**
 * Adds the default message marking select to the user profile edit form.
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.message.marking
 * @subpackage  system.event.listener
 * @category    Community Framework
 */
class UserProfileEditFormMessageMarkingListener implements EventListener {
	/**
	 * default message marking id
	 *
	 * @var integer
	 */
	protected $defaultMessageMarkingID = 0;

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (MODULE_DISPLAY_MESSAGE_MARKINGS == 1) {
			if ($eventObj->activeCategory == 'profile') {
				if ($eventName == 'validate') {
					if (WCF::getUser()->getPermission('user.profile.rank.canSelectMessageMarking')) {
						if (isset($_POST['defaultMessageMarkingID'])) $this->defaultMessageMarkingID = intval($_POST['defaultMessageMarkingID']);

						// validate default message marking id
						if ($this->defaultMessageMarkingID) {
							try {
								$availableMarkings = MessageMarking::getAvailableMarkings();
								if (!isset($availableMarkings[$this->defaultMessageMarkingID])) $this->defaultMessageMarkingID = 0;

								// save groupID
								$eventObj->additionalFields['defaultMessageMarkingID'] = $this->defaultMessageMarkingID;
							}
							catch (UserInputException $e) {
								$eventObj->errorType[$e->getField()] = $e->getType();
							}
						}
						else if ($this->defaultMessageMarkingID == 0) {
							// save groupID
							$eventObj->additionalFields['defaultMessageMarkingID'] = 0;
						}
					}
				}
				else if ($eventName == 'assignVariables') {
					if (!count($_POST)) {
						// get current value
						$this->defaultMessageMarkingID = WCF::getUser()->defaultMessageMarkingID;
					}
						
					$fields = array();
						
					// get message markings
					if (WCF::getUser()->getPermission('user.profile.rank.canSelectMessageMarking')) {
						$availableMarkings = MessageMarking::getAvailableMarkings();		

						if (count($availableMarkings)) {
							$sidebarFactory = new MessageSidebarFactory($this);
							$sidebarFactory->create(new DummySidebarObject());
							$sidebarFactory->init();
								
							$additionalCSS = '';
							foreach ($availableMarkings as $marking) {
								$additionalCSS .= $marking->getCSSoutput(array('#messageMarkingPreview'.$marking->markingID));
							}
							WCF::getTPL()->append('specialStyles', '<style type="text/css">'.$additionalCSS.'</style>');
								
							WCF::getTPL()->assign(array(
								'markings' => $availableMarkings,
								'defaultMessageMarkingID' => $this->defaultMessageMarkingID,
								'sidebarFactory' => $sidebarFactory
							));
							$fields[] = array(
								'optionName' => 'defaultMessageMarkingID',
								'divClass' => 'formRadio',
			                       			'beforeLabel' => false,
			                       			'isOptionGroup' => true,
			                        		'html' => WCF::getTPL()->fetch('userProfileEditMessageMarkingSelect')
							);
						}
					}

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
