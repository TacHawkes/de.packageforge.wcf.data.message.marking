<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/action/AbstractMessageMarkingAction.class.php');

/**
 * Enables a message marking.
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.message.marking
 * @subpackage  acp.action
 * @category    Community Framework
 */
class MessageMarkingEnableAction extends AbstractMessageMarkingAction {
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

		// check permission
		WCF::getUser()->checkPermission('admin.display.canEditMessageMarking');

		// enable marking
		$this->messageMarking->enable();

		// call executed
		$this->executed();

		// forward to list page
		HeaderUtil::redirect('index.php?page=MessageMarkingList&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>