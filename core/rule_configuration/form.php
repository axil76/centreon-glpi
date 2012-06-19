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

require_once "./modules/centreon-glpi/core/class/Centreon/Glpi/Client.php";

/**
 * Validates whether or not rule already exists
 * 
 * @param string $ruleName
 * @return bool
 */
function checkIfRuleExists($ruleName) {
    global $pearDB, $form;

    $ruleId = null;
    if (isset($form)) {
        $ruleId = $form->getSubmitValue('rule_id');
    }
    $res = $pearDB->query("SELECT rule_id, rule_name 
                           FROM mod_glpi_rules 
                           WHERE rule_name = '" . $pearDB->escape($ruleName) . "'");
    $row = $res->fetchRow();
    if ($res->numRows() >= 1 && $row["rule_id"] == $ruleId) {
        return true;
    } elseif ($res->numRows() >= 1 && $row["rule_id"] != $ruleId) {
        return false;
    } else {
        return true;
    }
}

try {

    /*
     * Database retrieve information for HostCategories
     */
    if (($o == "c") && $ruleId) {
        $ruleSettings = $ruleObj->getSettings($ruleId);
    }

    /*
     * Define Templatse
     */
    $attrsText = array("size" => "30");
    $attrsTextLong = array("size" => "50");
    $attrsAdvSelect = array("style" => "width: 220px; height: 220px;");
    $attrsTextarea = array("rows" => "4", "cols" => "60");
    $eTemplate = '<table><tr><td><div class="ams">{label_2}</div>{unselected}</td><td align="center">{add}<br /><br /><br />{remove}</td><td><div class="ams">{label_3}</div>{selected}</td></tr></table>';

    /*
     * Create formulary
     */
    $form = new HTML_QuickForm('Form', 'post', "?p=" . $p);
    if ($o == "a") {
        $form->addElement('header', 'title', _("Add a new GLPI matching rule"));
    } elseif ($o == "c") {
        $form->addElement('header', 'title', _("Modify a GLPI matching rule"));
    }

    /*
     * Basic Information
     */
    $form->addElement('header', 'information', _("General Information"));
    $form->addElement('text', 'rule_name', _("Rule Name"), $attrsText);
    $form->addElement('text', 'rule_description', _("Rule Description"), $attrsText);

    $form->addElement('header', 'relation', _("Relations"));

    /*
     * Instance Selection
     */
    $sql = "SELECT id, name FROM nagios_server ORDER BY name";
    $res = $pearDB->query($sql);
    $instances = array(null => null);
    while ($row = $res->fetchRow()) {
        $instances[$row['id']] = $row['name'];
    }
    $form->addElement('select', 'instance_id', _("Poller"), $instances);

    /*
     * Host template Selection
     */
    $sql = "SELECT host_id, host_name FROM host WHERE host_register = '0' ORDER BY host_name";
    $res = $pearDB->query($sql);
    $hostTemplates = array(null => null);
    while ($row = $res->fetchRow()) {
        $hostTemplates[$row['host_id']] = $row['host_name'];
    }
    $form->addElement('select', 'host_template_id', _("Host template"), $hostTemplates);

    /*
     * Hostgroup Selection
     */
    $sql = "SELECT hg_id, hg_name FROM hostgroup ORDER BY hg_name";
    $res = $pearDB->query($sql);
    $hostgroups = array();
    while ($row = $res->fetchRow()) {
        $hostgroups[$row['hg_id']] = $row['hg_name'];
    }
    $form->addElement('header', 'relation', _("Relations"));
    $ams1 = $form->addElement('advmultiselect', 'hostgroups', array(_("Linked Host Groups"), _("Available"), _("Selected")), $hostgroups, $attrsAdvSelect, SORT_ASC);
    $ams1->setButtonAttributes('add', array('value' => _("Add")));
    $ams1->setButtonAttributes('remove', array('value' => _("Delete")));
    $ams1->setElementTemplate($eTemplate);
    echo $ams1->getElementJs(false);

    /*
     * Host category Selection
     */
    $sql = "SELECT hc_id, hc_name FROM hostcategories ORDER BY hc_name";
    $res = $pearDB->query($sql);
    $hostcategories = array();
    while ($row = $res->fetchRow()) {
        $hostcategories[$row['hc_id']] = $row['hc_name'];
    }
    $ams1 = $form->addElement('advmultiselect', 'hostcategories', array(_("Linked Host Categories"), _("Available"), _("Selected")), $hostcategories, $attrsAdvSelect, SORT_ASC);
    $ams1->setButtonAttributes('add', array('value' => _("Add")));
    $ams1->setButtonAttributes('remove', array('value' => _("Delete")));
    $ams1->setElementTemplate($eTemplate);
    echo $ams1->getElementJs(false);

    $form->addElement('text', 'ip_range', _("IP Range (not applied to Network Equipment)"), $attrsText);
    
    $form->addElement('header', 'rules_header', _("Rules"));

    /*
     * GLPI rules
     */
    $glpiClient = new Centreon_Glpi_Client($pearDB);

    /*
     * Equipment name
     */
    $form->addElement('text', 'dropdowns[name]', _("Equipment Name"), $attrsText);

    /*
     * Location
     */
    $resultSet = $glpiClient->listDropdownValues(array('dropdown' => 'Location'));
    $locations = array(null => null);
    foreach ($resultSet as $result) {
        $locations[$result['id']] = $result['name'];
    }
    $form->addElement('select', 'dropdowns[locations_id]', _("Location"), $locations);

    /*
     * Technician in charge of the hardware
     */
    $resultSet = $glpiClient->listDropdownValues(array('dropdown' => 'User'));
    $users = array(null => null);
    foreach ($resultSet as $result) {
        $users[$result['id']] = $result['name'];
    }
    $form->addElement('select', 'dropdowns[users_id_tech]', _("Technician in charge of the hardware"), $users);
    $form->addElement('select', 'dropdowns[users_id]', _("User"), $users);

    /*
     * Group in charge of the hardware
     */
    $resultSet = $glpiClient->listDropdownValues(array('dropdown' => 'Group'));
    $usergroups = array(null => null);
    foreach ($resultSet as $result) {
        $usergroups[$result['id']] = $result['name'];
    }
    $form->addElement('select', 'dropdowns[groups_id_tech]', _("Group in charge of the hardware"), $usergroups);
    $form->addElement('select', 'dropdowns[groups_id]', _("Group"), $usergroups);

    /*
     * Domain
     */
    $resultSet = $glpiClient->listDropdownValues(array('dropdown' => 'Domain'));
    $domains = array(null => null);
    foreach ($resultSet as $result) {
        $domains[$result['id']] = $result['name'];
    }
    $form->addElement('select', 'dropdowns[domains_id]', _("Domain"), $domains);

    /*
     * Network
     */
    $resultSet = $glpiClient->listDropdownValues(array('dropdown' => 'Network'));
    $networks = array(null => null);
    foreach ($resultSet as $result) {
        $networks[$result['id']] = $result['name'];
    }
    $form->addElement('select', 'dropdowns[network_id]', _("Network"), $networks);

    /*
     * Network equipment types
     */
    $resultSet = $glpiClient->listDropdownValues(array('dropdown' => 'NetworkEquipmentType'));
    $networkEquipmentTypes = array(null => null);
    foreach ($resultSet as $result) {
        $networkEquipmentTypes[$result['id']] = $result['name'];
    }
    $form->addElement('select', 'dropdowns[networkequipmenttypes_id]', _("Network Equipment Type"), $networkEquipmentTypes);

    /*
     * Network equipment models
     */
    $resultSet = $glpiClient->listDropdownValues(array('dropdown' => 'NetworkEquipmentModel'));
    $networkEquipmentModels = array(null => null);
    foreach ($resultSet as $result) {
        $networkEquipmentModels[$result['id']] = $result['name'];
    }
    $form->addElement('select', 'dropdowns[networkequipmentmodels_id]', _("Network Equipment Model"), $networkEquipmentModels);

    /*
     * Status
     */
    $resultSet = $glpiClient->listDropdownValues(array('dropdown' => 'State'));
    $networkStates = array(null => null);
    foreach ($resultSet as $result) {
        $networkStates[$result['id']] = $result['name'];
    }
    $form->addElement('select', 'dropdowns[states_id]', _("Status"), $networkStates);

    /*
     * Manufacturers
     */
    $resultSet = $glpiClient->listDropdownValues(array('dropdown' => 'Manufacturer'));
    $manufacturers = array(null => null);
    foreach ($resultSet as $result) {
        $manufacturers[$result['id']] = $result['name'];
    }
    $form->addElement('select', 'dropdowns[manufacturers_id]', _("Manufacturer"), $manufacturers);

    $hid = $form->addElement('hidden', 'rule_id');
    if ($ruleId) {
        $hid->setValue($ruleId);
    }
    $redirect = $form->addElement('hidden', 'o');
    $redirect->setValue($o);

    $compulsoryField = _('Compulsory field');
    $form->addRule('rule_name', $compulsoryField, 'required');
    $form->addRule('rule_description', $compulsoryField, 'required');
    $form->addRule('host_template_id', $compulsoryField, 'required');
    $form->addRule('instance_id', $compulsoryField, 'required');

    $form->registerRule('exist', 'callback', 'checkIfRuleExists');
    $form->addRule('rule_name', _("Name is already in use"), 'exist');
    $form->setRequiredNote("<font style='color: red;'>*</font>" . _(" Required fields"));

    /*
     * Smarty template Init
     */
    $tpl = new Smarty();
    $tpl = initSmartyTpl($path, $tpl);

    if ($o == "c" && $ruleId) {
        $subC = $form->addElement('submit', 'submitC', _("Save"));
        $res = $form->addElement('reset', 'reset', _("Reset"));
        $form->setDefaults($ruleSettings);
    } elseif ($o == "a") {
        $subA = $form->addElement('submit', 'submitA', _("Save"));
        $res = $form->addElement('reset', 'reset', _("Reset"));
    }
    $tpl->assign('p', $p);

    $valid = false;
    if ($form->validate()) {
        $tmp = $form->getElement('rule_id');
        if ($form->getSubmitValue("submitA")) {
            $tmp->setValue($ruleObj->insert($form->getSubmitValues()));
        } elseif ($form->getSubmitValue("submitC")) {
            if ($ruleId) {
                $ruleObj->update($ruleId, $form->getSubmitValues());
            }
        }
        $o = NULL;
        $form->freeze();
        $valid = true;
    }

    if ($valid) {
        require_once($path . "list.php");
    } else {
        /*
         * Apply a template definition
         */
        $renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl, true);
        $renderer->setRequiredTemplate('{$label}&nbsp;<font color="red" size="1">*</font>');
        $renderer->setErrorTemplate('<font color="red">{$error}</font><br />{$html}');
        $form->accept($renderer);
        $tpl->assign('form', $renderer->toArray());
        $tpl->assign('o', $o);
        $tpl->assign('topdoc', _("Documentation"));
        $tpl->display("form.tpl");
    }
} catch (Exception $e) {
    echo "GLPI: ".$e->getMessage()."<br/>";
    echo "Please check your GLPI Web Service configuration <a href='./main.php?p=50138'>-- here --</a>.";
}
?>