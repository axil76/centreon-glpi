{$form.javascript}
{$colorJS}
<form {$form.attributes}>
    <table class="ListTable">
        <tr class="ListHeader"><td class="FormHeader" colspan="2">&nbsp;<img src='./img/icones/16x16/tool.gif'>&nbsp;{$form.header.title}</td></tr> 	
        <tr class="list_lvl_1"><td class="ListColLvl1_name" colspan="2">&nbsp;<img src='./modules/centreon-glpi/img/glpi.gif'>&nbsp;&nbsp;{$form.header.glpi}</td></tr>
        <tr class="list_one"><td class="FormRowField"><img class="helpTooltip" name="glpi_url"> {$form.glpi_url.label}</td><td class="FormRowValue">{$form.glpi_url.html}</td></tr>
	<tr class="list_two"><td class="FormRowField"><img class="helpTooltip" name="api_user"> {$form.api_user.label}</td><td class="FormRowValue">{$form.api_user.html}</td></tr>
        <tr class="list_one"><td class="FormRowField"><img class="helpTooltip" name="api_pass"> {$form.api_pass.label}</td><td class="FormRowValue">{$form.api_pass.html}</td></tr>
        <tr class="list_two"><td class="FormRowField"><img class="helpTooltip" name="admin_login"> {$form.admin_login.label}</td><td class="FormRowValue">{$form.admin_login.html}</td></tr>
        <tr class="list_one"><td class="FormRowField"><img class="helpTooltip" name="admin_pass"> {$form.admin_pass.label}</td><td class="FormRowValue">{$form.admin_pass.html}</td></tr>
        <tr class="list_two"><td class="FormRowField"><img class="helpTooltip" name="test_api"> {$form.test_api.html}</td><td class="FormRowValue" id='testResult'></td></tr>
        <tr class="list_lvl_1"><td class="ListColLvl1_name" colspan="2">&nbsp;<img src='./img/icones/16x16/centreon.gif'>&nbsp;&nbsp;{$form.header.centreon}</td></tr>
        <tr class="list_one"><td class="FormRowField"><img class="helpTooltip" name="clapi_login"> {$form.clapi_login.label}</td><td class="FormRowValue">{$form.clapi_login.html}</td></tr>
        <tr class="list_two"><td class="FormRowField"><img class="helpTooltip" name="clapi_pass"> {$form.clapi_pass.label}</td><td class="FormRowValue">{$form.clapi_pass.html}</td></tr>
        <tr class="list_one"><td class="FormRowField"><img class="helpTooltip" name="clapi_restart"> {$form.clapi_restart.label}</td><td class="FormRowValue">{$form.clapi_restart.html}</td></tr>
    </table>
    {if !$valid}
        <div id="validForm">
            <p>{$form.submitC.html}&nbsp;&nbsp;&nbsp;{$form.reset.html}</p>
        </div>
    {else}
        <div id="validForm">
            <p>{$form.change.html}</p>
        </div>
    {/if}
    {$form.hidden}
</form>
{$helptext}