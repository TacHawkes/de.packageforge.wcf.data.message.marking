<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');
require_once(WCF_DIR.'lib/data/user/group/Group.class.php');
require_once(WCF_DIR.'lib/data/message/marking/MessageMarkingEditor.class.php');

/**
 * Shows the form for adding message markings.
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.message.marking
 * @subpackage  acp.form
 * @category    Community Framework
 */
class MessageMarkingAddForm extends ACPForm {
	// system
	public $templateName = 'messageMarkingAdd';
	public $activeMenuItem = 'wcf.acp.menu.link.messageMarking.add';
	public $neededPermissions = 'admin.display.canAddMessageMarking';

	// parameters
	public $title = '';
	public $css = '';
	public $groupIDs = array();
	public $messageMarking = null;

	// form parameters
	public $groupSelect = array();

	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();

		if (isset($_POST['title'])) $this->title = StringUtil::trim($_POST['title']);
		if (isset($_POST['css'])) $this->css = StringUtil::trim($_POST['css']);
		if (isset($_POST['groupIDs']) && is_array($_POST['groupIDs'])) $this->groupIDs = ArrayUtil::toIntegerArray($_POST['groupIDs']);
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();

		$this->groupSelect = Group::getAccessibleGroups();
	}

	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();

		// check title
		if (empty($this->title)) {
			throw new UserInputException('title');
		}

		// validate group ids
		foreach ($this->groupIDs as $key => $groupID) {
			$group = new Group($groupID);
				
			if (!$group->groupID || !$group->isAccessible()) {
				unset($this->groupIDs[$key]);
			}
		}

		// if groupIDs is empty add everyone
		if (empty($this->groupIDs)) {
			$this->groupIDs = array(Group::EVERYONE);
		}
	}

	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();

		// save
		$this->messageMarking = MessageMarkingEditor::create($this->title, $this->css, $this->groupIDs);

		// clear cache
		WCF::getCache()->clear(WCF_DIR.'cache/', 'cache.messageMarkings.php');
		$this->saved();

		// reset values
		$this->title = '';
		$this->css = '';
		$this->groupIDs = array();

		// show success message
		WCF::getTPL()->assign('success', true);
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
			'action' => 'add',
			'title' => $this->title,
			'css' => $this->css,
			'groupSelect' => $this->groupSelect,
			'groupIDs' => $this->groupIDs			
		));
	}
}
?>