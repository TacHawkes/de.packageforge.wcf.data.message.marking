<?php
// wcf imports
require_once(WCF_DIR.'lib/page/SortablePage.class.php');
require_once(WCF_DIR.'lib/data/message/marking/MessageMarkingList.class.php');

/**
 * Shows a list of message markings.
 * 
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.message.marking
 * @subpackage  acp.page
 * @category    Community Framework
 */
class MessageMarkingListPage extends SortablePage {
	// system
	public $templateName = 'messageMarkingList';
	public $defaultSortField = 'markingID';
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

		// init list
		$this->markingList = new MessageMarkingList();
		
		if (isset($_REQUEST['deletedMarkingID'])) $this->deletedMarkingID = intval($_REQUEST['deletedMarkingID']);
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		$this->markingList = new MessageMarkingList();
		$this->markingList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
		$this->markingList->sqlLimit = $this->itemsPerPage;
		$this->markingList->readObjects();
	}
	
	/**
	 * @see SortablePage::validateSortField()
	 */
	public function validateSortField() {
		parent::validateSortField();

		switch ($this->sortField) {
			case 'markingID':
			case 'title':
				break;
			default: $this->sortField = $this->defaultSortField;
		}
	}
	
	/**
	 * @see MultipleLinkPage::countItems()
	 */
	public function countItems() {
		parent::countItems();

		return $this->markingList->countObjects();
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'markings' => $this->markingList->getObjects(),
			'deletedMarkingID' => $this->deletedMarkingID			
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.messageMarking.view');
		
		// check permission
		WCF::getUser()->checkPermission(array('admin.display.canEditMessageMarking', 'admin.display.canDeleteMessageMarking'));
		
		parent::show();
	}
}
