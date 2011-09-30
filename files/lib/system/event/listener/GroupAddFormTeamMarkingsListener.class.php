<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/acp/form/GroupEditForm.class.php');

/**
 * Appends the group add form with team marking fields.
 *
 * @author      Oliver Kliebisch, Markus Bartz
 * @copyright   2011 Oliver Kliebisch, Markus Bartz
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.markteam
 * @subpackage  system.event.listener
 * @category    Community Framework
 */
class GroupAddFormTeamMarkingsListener implements EventListener {
	/**
	 * mark this group as team
	 *
	 * @var integer
	 */
	protected $markAsTeam = 0;

	/**
	 * additional css for this group
	 *
	 * @var unknown_type
	 */
	protected $markAsTeamCSS = '';

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (MODULE_USER_MARK_TEAM_MESSAGE == 1) {
			if ($eventName == 'readFormParameters') {
				if (isset($_POST['markAsTeam'])) $this->markAsTeam = intval($_POST['markAsTeam']);
				if (isset($_POST['markAsTeamCSS'])) $this->markAsTeamCSS = StringUtil::trim($_POST['markAsTeamCSS']);
			}
			else if ($eventName == 'save') {
				$eventObj->additionalFields['markAsTeam'] = $this->markAsTeam;
				$eventObj->additionalFields['markAsTeamCSS'] = $this->markAsTeamCSS;
				if (!($eventObj instanceof GroupEditForm)) {
					$this->markAsTeam = 0;
					$this->markAsTeamCSS = '';
				}
					
				WCF::getCache()->clear(WCF_DIR.'cache', 'cache.teamgroups.php', true);
			}
			else if ($eventName == 'assignVariables') {
				if (!count($_POST) && $eventObj instanceof GroupEditForm) {
					$this->markAsTeam = $eventObj->group->markAsTeam;
					$this->markAsTeamCSS = $eventObj->group->markAsTeamCSS;
				}
				WCF::getTPL()->assign(array(
				'markAsTeam' => $this->markAsTeam,
				'markAsTeamCSS' => $this->markAsTeamCSS
				));
				WCF::getTPL()->append('additionalFields', WCF::getTPL()->fetch('groupAddMarkAsTeam'));
			}
		}
	}
}
