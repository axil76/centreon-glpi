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

if (file_exists('./modules/centreon-glpi/core/class/Centreon/Glpi/Api.php')) {
    require_once './modules/centreon-glpi/core/class/Centreon/Glpi/Api.php';
} else {
    require_once $centreon_path .'www/modules/centreon-glpi/core/class/Centreon/Glpi/Api.php';
}

/**
 * GLPI Soap Client
 *
 * @author shotamchay
 */
class Centreon_Glpi_Client {
    protected $db;
    protected $client;
    protected $methodPrefix;
    
    /**
     * Constructor
     * 
     * @param CentreonDB $db
     * @param array $params
     * @return void 
     * @throws Exception
     */
    public function __construct($db = null, $params = null)
    {
        $this->db = $db;
        $this->methodPrefix = "glpi.";
        if (is_null($params)) {
            $params = Centreon_Glpi_Api::getParams($db);
        }
        $uri = $params['glpi_url']."/plugins/webservices/soap.php";
        try {
            $this->client = new SoapClient(null, array('uri'      => $uri,
                                                    'location' => $uri));
            $res = $this->genericCall("doLogin", array('username'       => $params['api_user'],
                                                    'password'       => $params['api_pass'],
                                                    'login_name'     => $params['admin_login'],
                                                    'login_password' => $params['admin_pass']));
        } catch (SoapFault $e) {
            throw new Exception($e->getMessage());
        }
        if (isset($res['session']) && $res['session']) {
            $this->glpiSession  = $res['session'];
        }
    }
    
    /**
     * Generic soap call
     * 
     * @param string $method
     * @param array $params
     * @param string $paramValue
     */
    protected function genericCall($method, $params = array())
    {        
        if (isset($this->glpiSession)) {
            $args = array('method'  => $this->methodPrefix.$method,
                          'session' => $this->glpiSession);
        } else {
            $args = array('method'  => $this->methodPrefix.$method);
        }
        foreach ($params as $k => $v) {
            if ($v == "") {
                $v = true;
            }
            $args[$k] = $v;
        }
        $result = $this->client->__soapCall('genericExecute', array(new SoapParam($args, 'params')));
        return $result;
    }
    
    /**
     * Magic __call method
     * 
     * @param string $method 
     * @param array $args
     * @return array
     */
    public function __call($method, $args)
    {
        if (!isset($args[0]) || !is_array($args[0])) {
            throw new Exception('Method expects an argument of array type');
        }
        return $this->genericCall($method, $args[0]);
    }
}
?>
