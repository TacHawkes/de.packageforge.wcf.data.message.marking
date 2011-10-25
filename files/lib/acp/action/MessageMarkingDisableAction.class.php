<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/action/AbstractMessageMarkingAction.class.php');

/**
 * Disables a message marking.
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.message.marking
 * @subpackage  acp.action
 * @category    Community Framework
 */
class MessageMarkingDisableAction extends AbstractMessageMarkingAction {
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

		// check permission
		WCF::getUser()->checkPermission('admin.display.canEditMessageMarking');

		// disable marking
		$this->messageMarking->disable();

		// call executed
		$this->executed();

		// forward to list page
		HeaderUtil::redirect('index.php?page=MessageMarkingList&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>