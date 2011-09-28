<?php
// wcf imports
require_once(WCF_DIR.'lib/data/message/sidebar/MessageSidebarObject.class.php');
require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');

/**
 * Represents a viewable post in the forum.
 *
 * @author 	Marcel Werk
 * @copyright	2001-2009 WoltLab GmbH
 * @license	WoltLab Burning Board License <http://www.woltlab.com/products/burning_board/license.php>
 * @package	com.woltlab.wbb
 * @subpackage	data.post
 * @category 	Burning Board
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