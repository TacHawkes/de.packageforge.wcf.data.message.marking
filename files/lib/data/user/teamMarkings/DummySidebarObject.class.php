<?php
// wcf imports
require_once(WCF_DIR.'lib/data/message/sidebar/MessageSidebarObject.class.php');
require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');

/**
 * Simple dummy object for the message preview
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.markteam
 * @subpackage  data.user.teamMarkings
 * @category    Community Framework
 */
class DummySidebarObject implements MessageSidebarObject {
	/**
	 * user object
	 *
	 * @var UserProfile
	 */
	protected $user = null;

	/**
	 * Magic get method
	 *
	 * @return null
	 */
	public function __get($name) {
		return null;
	}

	// MessageSidebarObject implementation
	/**
	 * @see MessageSidebarObject::getUser()
	 */
	public function getUser() {
		if ($this->user === null) {
			$this->user = new UserProfile(WCF::getUser()->userID);
		}
		return $this->user;
	}

	/**
	 * @see MessageSidebarObject::getMessageID()
	 */
	public function getMessageID() {
		return 0;
	}

	/**
	 * @see MessageSidebarObject::getMessageType()
	 */
	public function getMessageType() {
		return 'dummy';
	}
}
?>