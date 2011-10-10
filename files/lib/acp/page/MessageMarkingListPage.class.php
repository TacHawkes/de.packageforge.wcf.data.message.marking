<?php
// wcf imports
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/data/message/marking/MessageMarkingList.class.php');

/**
 * Shows a list of message markings.
 * 
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.markteam
 * @subpackage  data.message.marking
 * @category    Community Framework
 */
class MessageMarkingListPage extends AbstractPage {
	// system
	public $templateName = 'messageMarkingList';
	public $deletedMarkingID = 0;
	
	/**
	 * MessageMarkingList object
	 * 
	 * @var	MessageMarkingList
	 */
	public $markingList = array();
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['deletedMarkingID'])) $this->deletedMarkingID = intval($_REQUEST['deletedMarkingID']);
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		$this->markingList = new MessageMarkingList();
		$this->markingList->sqlLimit = 0;
		$this->markingList->readObjects();
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'markings' => $this->markingList->getObjects(),
			'deletedMarkingID' => $this->deletedMarkingID,
			'items' => $this->markingList->countObjects()
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.messageMarking.markingList');
		
		// check permission
		WCF::getUser()->checkPermission(array('admin.messageMarking.canEditMarking', 'admin.book.canDeleteMarking'));
		
		parent::show();
	}
}
?>