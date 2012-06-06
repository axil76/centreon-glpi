<?php
/*
 * Copyright 2005-2012 MERETHIS
 * Centreon is developped by : Julien Mathis and Romain Le Merlus under
 * GPL Licence 2.0.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation ; either version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see <http://www.gnu.org/licenses>.
 *
 * Linking this program statically or dynamically with other modules is making a
 * combined work based on this program. Thus, the terms and conditions of the GNU
 * General Public License cover the whole combination.
 *
 * As a special exception, the copyright holders of this program give MERETHIS
 * permission to link this program with independent modules to produce an executable,
 * regardless of the license terms of these independent modules, and to copy and
 * distribute the resulting executable under terms of MERETHIS choice, provided that
 * MERETHIS also meet, for each linked independent module, the terms  and conditions
 * of the license of that module. An independent module is a module which is not
 * derived from this program. If you modify this program, you may extend this
 * exception to your version of the program, but you are not obliged to do so. If you
 * do not wish to do so, delete this exception statement from your version.
 *
 * For more information : contact@centreon.com
 *
 * SVN : $URL$
 * SVN : $Id$
 *
 */

if (!isset($oreon)) {
    exit();
}

$ruleId = null;
if (isset($_REQUEST['rule_id'])) {
    $ruleId = $_REQUEST['rule_id'];
}

$select = null;
if (isset($_REQUEST['select'])) {
    $select = $_REQUEST['select'];
}

$dupNbr = null;
if (isset($_REQUEST['dupNbr'])) {
    $dupNbr = $_REQUEST['dupNbr'];
}

/*
 * Pear library
 */
require_once "HTML/QuickForm.php";
require_once 'HTML/QuickForm/advmultiselect.php';
require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

/*
 * Path to the configuration dir
 */
$path = "./modules/centreon-glpi/core/rule_configuration/";

require_once "./modules/centreon-glpi/core/class/Centreon/Glpi/Rule.php";

$ruleObj = new Centreon_Glpi_Rule($pearDB);

switch ($o) {
    case "a":
        require_once $path."form.php";
        break;
    case "c":
        require_once $path."form.php";
        break;
    case "d":        
        $ruleObj->delete($select);
        require_once $path."list.php";
        break;
    case "m":
        $ruleObj->duplicate($select, $dupNbr);
        require_once $path."list.php";
        break;
    case "s":
        $ruleObj->enable($ruleId);
        require_once $path."list.php";
        break;
    case "u":
        $ruleObj->disable($ruleId);
        require_once $path."list.php";
        break;
    case "ms":
        $ruleObj->enable($select);
        require_once $path."list.php";
        break;
    case "mu":
        $ruleObj->disable($select);
        require_once $path."list.php";
        break;
    default:
        require_once $path."list.php";        
        break;
}
?>