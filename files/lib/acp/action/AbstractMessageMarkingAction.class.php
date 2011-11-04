<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');
require_once(WCF_DIR.'lib/data/message/marking/MessageMarkingEditor.class.php');

/**
 * Provides default implementations for message marking actions.
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.message.marking
 * @subpackage  acp.action
 * @category    Community Framework
 */
abstract class AbstractMessageMarkingAction extends AbstractAction {
	/**
	 * marking id
	 *
	 * @var	integer
	 */
	public $markingID = 0;

	/**
	 * message marking editor object
	 *
	 * @var	MessageMarkingEditor
	 */
	public $messageMarking = null;

	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// read marking
		if (isset($_REQUEST['markingID'])) $this->markingID = intval($_REQUEST['markingID']);
		$this->messageMarking = new MessageMarkingEditor($this->markingID);
		if (!$this->messageMarking->markingID) {
			throw new IllegalLinkException();
		}
	}

	/**
	 * @see Action::executed()
	 */
	public function executed() {
		parent::executed();

		// clear cache
		WCF::getCache()->clear(WCF_DIR.'cache/', 'cache.messageMarkings.php');
	}
}
