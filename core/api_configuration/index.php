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

$path = "./modules/centreon-glpi/core/api_configuration/";

require_once './modules/centreon-glpi/core/class/Centreon/Glpi/Api.php';

$glpiConf = Centreon_Glpi_Api::getParams($pearDB);

/*
 * Pear library
 */
require_once "HTML/QuickForm.php";
require_once 'HTML/QuickForm/advmultiselect.php';
require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';

/*
 * Style
 */
$attrsText = array("size" => "40");
$attrsText2 = array("size" => "5");
$attrsAdvSelect = null;

/*
 * Form begin
 */
$form = new HTML_QuickForm('Form', 'post', "?p=" . $p);
$form->addElement('header', 'title', _("API Configuration"));

/*
 * information
 */
$form->addElement('header', 'glpi', _("GLPI WS Server"));
$form->addElement('text', 'glpi_url', _("URL"), $attrsText);
$form->addElement('text', 'api_user', _("API Secure Username"), $attrsText);
$form->addElement('password', 'api_pass', _("API Secure Password"), $attrsText);
$form->addElement('text', 'admin_login', _("Admin Login"), $attrsText);
$form->addElement('password', 'admin_pass', _("Admin Password"), $attrsText);
$form->addElement('header', 'centreon', _("Centreon CLAPI"));
$form->addElement('text', 'clapi_login', _("Admin Login"), $attrsText);
$form->addElement('password', 'clapi_pass', _("Admin Password"), $attrsText);
$clapiRestartchoices = array();
$clapiRestartchoices[] = HTML_QuickForm::createElement('radio', null, null, _("Yes"), '1');
$clapiRestartchoices[] = HTML_QuickForm::createElement('radio', null, null, _("No"), '0');
$form->addGroup($clapiRestartchoices, 'clapi_restart', _("Restart CLAPI after import"), '&nbsp;');

$compulsoryField = _('Compulsory field');
$form->addRule('glpi_url', $compulsoryField, 'required');
$form->addRule('admin_login', $compulsoryField, 'required');
$form->addRule('admin_pass', $compulsoryField, 'required');
$form->addRule('clapi_login', $compulsoryField, 'required');
$form->addRule('clapi_pass', $compulsoryField, 'required');

/*
 * Smarty template Init
 */
$tpl = new Smarty();
$tpl = initSmartyTpl($path, $tpl);

$form->setDefaults($glpiConf);

$subC = $form->addElement('submit', 'submitC', _("Save"));
$form->addElement('reset', 'reset', _("Reset"));

$valid = false;
if ($form->validate()) {
    Centreon_Glpi_Api::setParams($pearDB, $form->getSubmitValues());
    $o = NULL;
    $valid = true;
    $form->freeze();
}

if (!$form->validate() && isset($_POST['o'])) {
    print("<div class='msg' align='center'>" . _("Impossible to validate, one or more field is incorrect") . "</div>");
}
$form->addElement("button", "test_api", _("Test API"), array("onClick" => "javascript:testApi();"));
$form->addElement("button", "change", _("Modify"), array("onClick" => "javascript:window.location.href='?p=" . $p . "'"));

/*
 * Send variable to template
 */
$tpl->assign('o', $o);
$tpl->assign('valid', $valid);

/*
 * Help text
 */
$tpl->assign("helpattr", 'TITLE, "'._("Help").'", CLOSEBTN, true, FIX, [this, 0, 5], BGCOLOR, "#ffff99", BORDERCOLOR, "orange", TITLEFONTCOLOR, "black", TITLEBGCOLOR, "orange", CLOSEBTNCOLORS, ["","black", "white", "red"], WIDTH, -300, SHADOW, true, TEXTALIGN, "justify"' );
$helptext = "";
include_once $path."help.php";
foreach ($help as $key => $text) {
    $helptext .= '<span style="display:none" id="help:'.$key.'">'.$text.'</span>'."\n";
}
$tpl->assign("helptext", $helptext);

/*
 * Apply a template definition
 */
$renderer = new HTML_QuickForm_Renderer_ArraySmarty($tpl);
$renderer->setRequiredTemplate('{$label}&nbsp;<font color="red" size="1">*</font>');
$renderer->setErrorTemplate('<font color="red">{$error}</font><br />{$html}');
$form->accept($renderer);
$tpl->assign('form', $renderer->toArray());
$tpl->display("index.tpl");
?>
<script type='text/javascript'>
    function testApi() {
        $('testResult').update("<img src='./img/misc/ajax-loader.gif'>");
        new Ajax.Request('./modules/centreon-glpi/core/api_configuration/test.php', {
            method: 'post',
            parameters: $('Form').serialize(),
            onSuccess: function(transport) {
                if (transport.responseText.match(/SUCCESS/)) {
                    $('testResult').update("<font color='green'>Successfully called Web Service</font>");
                } else {
                    $('testResult').update("<font color='red'>"+transport.responseText+"</font>");
                }
            }
        });
        
    }
</script>