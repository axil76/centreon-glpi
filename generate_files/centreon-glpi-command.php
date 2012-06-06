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
    exit;
}

require_once $centreon_path . "www/class/centreonCfgWriter.class.php";
require_once $centreon_path . "www/modules/centreon-glpi/core/class/Centreon/Glpi/Api.php";

$cfg = new CentreonCfgWriter($oreon, $nagiosCFGPath . "/" . $tab['id'] . "/centreon-glpi-command.cfg");
$cfg->createCfgFile();

if (isset($tab['localhost']) && $tab['localhost']) {
    $apiParams = Centreon_Glpi_Api::getParams($pearDB);
    if (isset($apiParams['glpi_url']) && $apiParams['glpi_url']) {
        $configurationFile = $oreon->optGen["nagios_path_plugins"] . "glpi/webservices.ini";
        $content  = "glpi_url=".$apiParams['glpi_url']."\n";
        $content .= "admin_login=".$apiParams['admin_login']."\n";
        $content .= "admin_pass=".$apiParams['admin_pass']."\n";
        $content .= "api_user=".$apiParams['api_user']."\n";
        $content .= "api_pass=".$apiParams['api_pass']."\n";
        file_put_contents($configurationFile, $content);        
    }
}
?>
