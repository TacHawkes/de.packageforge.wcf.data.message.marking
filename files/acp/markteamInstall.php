<?php
/**
 * Activates group permissions for admins
 *
 * @author      Oliver Kliebisch
 * @copyright   2011 Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     de.packageforge.wcf.markteam
 * @subpackage  acp
 * @category    Community Framework
 */
// admin options
$sql = "UPDATE 	wcf".WCF_N."_group_option_value
	SET	optionValue = 1
	WHERE	groupID = 4
	AND     optionID IN     (
				SELECT	optionID
				FROM    wcf".WCF_N."_group_option
				WHERE	packageID = ".$this->installation->getPackageID()."
				)
                AND     optionValue = '0'";
WCF::getDB()->sendQuery($sql);
