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

/**
 * Centreon GLPI API parameters
 *
 * @author shotamchay
 */
class Centreon_Glpi_Api {
    /**
     * Constructor
     * 
     * @return void
     */
    private function __construct()
    {
        
    }
    
    /**
     * Set parameters
     * 
     * @param CentreonDB $db
     * @param array $params
     * @return void
     */
    public static function setParams($db, $params = array())
    {
        $sql = "UPDATE mod_glpi_api SET
                glpi_url = '".$db->escape($params['glpi_url'])."',
                api_user = '".$db->escape($params['api_user'])."',
                api_pass = '".$db->escape($params['api_pass'])."',
                admin_login = '".$db->escape($params['admin_login'])."',
                admin_pass = '".$db->escape($params['admin_pass'])."',
                clapi_login = '".$db->escape($params['clapi_login'])."',
                clapi_pass = '".$db->escape($params['clapi_pass'])."',
                clapi_restart = '".$db->escape($params['clapi_restart'])."'";        
        $db->query($sql);
    }
    
    /**
     * Get parameters
     * 
     * @param CentreonDB $db
     * @return array
     */
    public static function getParams($db)
    {
        $sql = "SELECT glpi_url, api_user, api_pass, admin_login, admin_pass, clapi_login, clapi_pass, clapi_restart 
                FROM mod_glpi_api";
        $res = $db->query($sql);
        $params = array();
        while ($row = $res->fetchRow()) {
            foreach ($row as $k => $v) {
                $params[$k] = $v;
            }
        }
        return $params;
    }
}

?>
