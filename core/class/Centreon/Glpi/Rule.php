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
 * Description of Rule
 *
 * @author sho
 */
class Centreon_Glpi_Rule {
    protected $db;
    
    /**
     * Constructor 
     * 
     * @return void
     */
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    /**
     * Get list
     * 
     * @param array $filters
     * @param mixed $offset
     * @param mixed $limit
     * @param string $sort
     * @param string $order
     * @return mixed
     */
    public function getList($filters = array(), $offset = null, $limit = null, $sort = 'rule_name', $order = 'ASC')
    {
        if (is_null($offset) && is_null($limit)) {
            $sql = "SELECT COUNT(rule_id) as nb ";
        } else {
            $sql = "SELECT rule_id, rule_name, rule_description, ip_range, activate ";
        }
        $sql .= "FROM mod_glpi_rules ";
        if (count($filters)) {
            $first = true;
            foreach ($filters as $key => $val) {
                if ($first === true) {
                    $sql .= "WHERE ";
                    $first = false;
                } else {
                    $sql .= "AND ";
                }
                $sql .= $key . " LIKE '%".$this->db->escape($val)."%' ";
            }
        }
        if (!is_null($offset) && !is_null($limit)) {
            $sql .= "ORDER BY $sort $order ";
            $sql .= "LIMIT $offset, $limit ";
        }
        $res = $this->db->query($sql);
        if (is_null($offset) && is_null($limit)) {
            $row = $res->fetchRow();
            return $row['nb'];
        } else {
            $list = array();
            while ($row = $res->fetchRow()) {
                $list[] = $row;
            }
            return $list;
        }
    }
        
    /**
     * Enable rule(s)
     * 
     * @param mixed $rule Can be an integer or an array of integers
     * @return void
     */
    public function enable($rule)
    {
        $this->setStatus($rule, 1);
    }
    
    /**
     * Disable rule(s)
     * 
     * @param mixed $rule Can be an integer or an array of integers
     * @return void
     */
    public function disable($rule)
    {        
        $this->setStatus($rule, 0);
    }

    /**
     * Set status (enable or disable
     * 
     * @param mixed $rule
     * @param int $status
     * @return void
     */
    protected function setStatus($rule, $status)
    {
        if (is_array($rule)) {
            $rules = array_keys($rule);
            array_walk($rules, array($this->db, 'escape'));            
        } elseif ($rule) {
            $rules = array($this->db->escape($rule));
        }        
        if (isset($rules) && is_array($rules) && count($rules)) {
            $sql = "UPDATE mod_glpi_rules SET activate = '$status' WHERE rule_id IN (".implode(',', $rules).")";
            $this->db->query($sql);
        }
    }
    
    /**
     * Duplicate rule(s)
     * 
     * @param array $rules
     * @param array $duplicateNb
     * @return void
     */
    public function duplicate($rules = array(), $duplicateNb = array())
    {
        foreach ($rules as $ruleId => $noUse) {            
            if (isset($duplicateNb[$ruleId]) && $duplicateNb[$ruleId]) {
                for ($i = 1; $i <= $duplicateNb[$ruleId]; $i++) {
                    $res = $this->db->query("SELECT rule_id 
                                            FROM mod_glpi_rules 
                                            WHERE rule_name = (SELECT CONCAT_WS('_', rule_name, '$i') FROM mod_glpi_rules WHERE rule_id = ".$this->db->escape($ruleId).")");
                    if (!$res->numRows()) {
                        $sql = "INSERT INTO mod_glpi_rules (rule_name, rule_description, host_template_id, instance_id, activate) 
                                SELECT CONCAT_WS('_', rule_name, '$i'), rule_description, host_template_id, instance_id, activate FROM mod_glpi_rules 
                                WHERE rule_id = ".$this->db->escape($ruleId);
                        $this->db->query($sql);
                        $sql2 = "SELECT MAX(rule_id) AS last_id
                                FROM mod_glpi_rules 
                                WHERE rule_name = (SELECT CONCAT_WS('_', rule_name, '$i')
                                                   FROM mod_glpi_rules 
                                                   WHERE rule_id = ".$this->db->escape($ruleId).")";
                        $res2 = $this->db->query($sql2);
                        $row = $res2->fetchRow();
                        $lastId = $row['last_id'];
                        unset($res2);
                        $this->db->query("INSERT INTO mod_glpi_matching (rule_id, rule_dropdown_name, rule_dropdown_value) 
                                                                        SELECT $lastId, rule_dropdown_name, rule_dropdown_value
                                                                        FROM mod_glpi_matching
                                                                        WHERE rule_id = " . $this->db->escape($ruleId));
                        $this->db->query("INSERT INTO mod_glpi_rule_hg_relations (rule_id, hg_id) 
                                                                        SELECT $lastId, hg_id
                                                                        FROM mod_glpi_rule_hg_relations
                                                                        WHERE rule_id = " . $this->db->escape($ruleId));
                        $this->db->query("INSERT INTO mod_glpi_rule_hc_relations (rule_id, hc_id) 
                                                                        SELECT $lastId, hc_id
                                                                        FROM mod_glpi_rule_hc_relations
                                                                        WHERE rule_id = " . $this->db->escape($ruleId));
                    }
                    unset($res);
                }
            }
        }
    }
    
    /**
     * Delete rule(s)
     * 
     * @param array $rules
     * @return void
     */
    public function delete($rules = array())
    {
        $rules = array_keys($rules);
        array_walk($rules, array($this->db, 'escape'));                    
        if (count($rules)) {
            $sql = "DELETE FROM mod_glpi_rules WHERE rule_id IN (".implode(',', $rules).")";
            $this->db->query($sql);
        }
    }
    
    /**
     * Insert new rule
     * 
     * @param array $params     
     * @return int
     */
    public function insert($params = array())
    {        
        $sql = "INSERT INTO mod_glpi_rules (rule_name, rule_description, host_template_id, ip_range, instance_id) 
                VALUES ('".$this->db->escape($params['rule_name'])."', 
                        '".$this->db->escape($params['rule_description'])."', 
                        ".$this->db->escape($params['host_template_id']).",
                        '".$this->db->escape($params['ip_range'])."', 
                        ".$this->db->escape($params['instance_id']).")";
        $this->db->query($sql);
        $sql2 = "SELECT MAX(rule_id) AS last_id
                 FROM mod_glpi_rules 
                 WHERE rule_name = '".$this->db->escape($params['rule_name'])."'";
        $res2 = $this->db->query($sql2); 
        $row = $res2->fetchRow();
        $lastId = $row['last_id'];
        unset($res2);
        if (isset($params['dropdowns'])) {
            $this->setDropdown($lastId, $params['dropdowns']);
        } else {
            $this->setDropdown($lastId);
        }
        if (isset($params['hostgroups'])) {
            $this->setHostGroups($lastId, $params['hostgroups']);
        } else {
            $this->setHostGroups($lastId);
        }
        if (isset($params['hostcategories'])) {
            $this->setHostCategories($lastId, $params['hostcategories']);
        } else {
            $this->setHostCategories($lastId);    
        }
        
    }
    
    /**
     * Update rule
     * 
     * @param int $ruleId
     * @param array $params
     * @return void
     */
    public function update($ruleId, $params = array())
    {
        $sql = "UPDATE mod_glpi_rules SET 
                    rule_name = '".$this->db->escape($params['rule_name'])."', 
                    rule_description = '".$this->db->escape($params['rule_description'])."', 
                    host_template_id = ".$this->db->escape($params['host_template_id']).", 
                    ip_range = '".$this->db->escape($params['ip_range'])."', 
                    instance_id = ".$this->db->escape($params['instance_id'])." 
                WHERE rule_id = ".$this->db->escape($ruleId);        
        $this->db->query($sql);
        if (isset($params['dropdowns'])) {
            $this->setDropdown($ruleId, $params['dropdowns']);
        } else {
            $this->setDropdown($ruleId);
        }
        if (isset($params['hostgroups'])) {
            $this->setHostGroups($ruleId, $params['hostgroups']);    
        } else {
            $this->setHostGroups($ruleId);
        }
        if (isset($params['hostcategories'])) {
            $this->setHostCategories($ruleId, $params['hostcategories']);
        } else {
            $this->setHostCategories($ruleId);
        }
    }
    
    /**
     * Set dropdown values 
     * 
     * @param int $ruleId
     * @param array $dropdowns
     * @return void
     */
    protected function setDropdown($ruleId, $dropdowns = array())
    {
        $this->db->query("DELETE FROM mod_glpi_matching WHERE rule_id = ".$this->db->escape($ruleId));
        if (count($dropdowns)) {
            $sql = "INSERT INTO mod_glpi_matching (rule_id, rule_dropdown_name, rule_dropdown_value) VALUES ";
            $i = 0;
            foreach ($dropdowns as $name => $value) {
                if ($i) {
                    $sql .= ", ";
                }
                $sql.= "(".$this->db->escape($ruleId).", '".$this->db->escape($name)."', '".$this->db->escape($value)."')";
                $i++;
            }
            if ($i) {
                $this->db->query($sql);
            }
        }
    }
    
    /**
     * Set hostgroups
     * 
     * @param int $ruleId
     * @param array $hostgroups
     * @return void
     */
    protected function setHostGroups($ruleId, $hostgroups = array())
    {
        $this->db->query("DELETE FROM mod_glpi_rule_hg_relations WHERE rule_id = ".$this->db->escape($ruleId));
        if (count($hostgroups)) {
            $sql = "INSERT INTO mod_glpi_rule_hg_relations (rule_id, hg_id) VALUES ";
            $i = 0;
            foreach ($hostgroups as $hgId) {
                if ($i) {
                    $sql .= ", ";
                }
                $sql.= "(".$this->db->escape($ruleId).", ".$this->db->escape($hgId).")";
                $i++;
            }
            if ($i) {
                $this->db->query($sql);
            }
        }
    }
    
    /**
     * Set host categories
     * 
     * @param int $ruleId
     * @param array $hostcategories
     * @return void
     */
    protected function setHostCategories($ruleId, $hostcategories = array())
    {
        $this->db->query("DELETE FROM mod_glpi_rule_hc_relations WHERE rule_id = ".$this->db->escape($ruleId));
        if (count($hostcategories)) {
            $sql = "INSERT INTO mod_glpi_rule_hc_relations (rule_id, hc_id) VALUES ";
            $i = 0;
            foreach ($hostcategories as $hcId) {
                if ($i) {
                    $sql .= ", ";
                }
                $sql.= "(".$this->db->escape($ruleId).", ".$this->db->escape($hcId).")";
                $i++;
            }
            if ($i) {
                $this->db->query($sql);
            }
        }
    }
    
    /**
     * Get settings
     * 
     * @param int $ruleId
     * @return array
     */
    public function getSettings($ruleId)
    {
        $settings = array();
        $sql = "SELECT rule_name, rule_description, host_template_id, ip_range, instance_id 
                FROM mod_glpi_rules 
                WHERE rule_id = ".$this->db->escape($ruleId);        
        $res = $this->db->query($sql);
        while ($row = $res->fetchRow()) {
            foreach ($row as $k => $v) {
                $settings[$k] = $v;
            }
        }
        $sql = "SELECT hg_id 
                FROM mod_glpi_rule_hg_relations
                WHERE rule_id = ".$this->db->escape($ruleId);
        $res = $this->db->query($sql);
        $settings['hostgroups'] = array();
        while ($row = $res->fetchRow()) {
            $settings['hostgroups'][] = $row['hg_id'];
        }
        $sql = "SELECT hc_id 
                FROM mod_glpi_rule_hc_relations
                WHERE rule_id = ".$this->db->escape($ruleId);
        $res = $this->db->query($sql);
        $settings['hostcategories'] = array();
        while ($row = $res->fetchRow()) {
            $settings['hostcategories'][] = $row['hc_id'];
        }
        
        $sql = "SELECT rule_dropdown_name, rule_dropdown_value 
                FROM mod_glpi_matching 
                WHERE rule_id = ".$this->db->escape($ruleId);
        $res = $this->db->query($sql);
        while ($row = $res->fetchRow()) {
            $settings['dropdowns['.$row['rule_dropdown_name'].']'] = $row['rule_dropdown_value'];
        }
        
        return $settings;
    }
}
?>
