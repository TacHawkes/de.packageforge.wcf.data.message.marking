<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/form/MessageMarkingAddForm.class.php');

/**
 * Shows the form for editing message markings.
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.message.marking
 * @subpackage  acp.form
 * @category    Community Framework
 */
class MessageMarkingEditForm extends MessageMarkingAddForm {
	// system
	public $activeMenuItem = 'wcf.acp.menu.link.messageMarking';
	public $neededPermissions = 'admin.display.canEditMessageMarking';

	// parameters
	public $markingID = 0;

	/**
	 * thread icon category editor object
	 *
	 * @var	MessageMarkingEditor
	 */
	public $messageMarking = null;

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		if (isset($_REQUEST['markingID'])) $this->markingID = intval($_REQUEST['markingID']);
		$this->messageMarking = new MessageMarkingEditor($this->markingID);
		if (!$this->messageMarking->markingID) {
			throw new IllegalLinkException();
		}
	}

	/**
	 * @see Form::save()
	 */
	public function save() {
		ACPForm::save();

		// save
		$this->messageMarking->update($this->title, $this->css, $this->groupIDs);

		// reset cache
		WCF::getCache()->clear(WCF_DIR.'cache/', 'cache.messageMarkings.php');
		$this->saved();

		// show success message
		WCF::getTPL()->assign('success', true);
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();

		if (!count($_POST)) {
			$this->title = $this->messageMarking->title;
			$this->css = $this->messageMarking->css;
			$this->groupIDs = explode(',', $this->messageMarking->groupIDs);
		}
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
			'action' => 'edit',
			'markingID' => $this->markingID,
			'messageMarking' => $this->messageMarking
		));
	}
}
