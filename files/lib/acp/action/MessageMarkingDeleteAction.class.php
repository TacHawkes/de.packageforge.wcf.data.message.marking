<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/action/AbstractMessageMarkingAction.class.php');

/**
 * Deletes a message marking.
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.markteam
 * @subpackage  acp.action
 * @category    Community Framework
 */
class MessageMarkingDeleteAction extends AbstractMessageMarkingAction {
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

		// check permission
		WCF::getUser()->checkPermission('admin.display.canDeleteMessageMarking');

		// delete marking
		$this->messageMarking->delete();
		
		// call executed
		$this->executed();

		// forward to list page
		HeaderUtil::redirect('index.php?page=MessageMarkingList&deletedMarkingID='.$this->markingID.'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>