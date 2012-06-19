{$form.javascript}
<form {$form.attributes}>
    <div id="validFormTop">
        {if $o == "a" || $o == "c"}
            <p class="oreonbutton">{$form.submitC.html}{$form.submitA.html}&nbsp;&nbsp;&nbsp;{$form.reset.html}</p>
        {else if $o == "w"}
            <p class="oreonbutton">{$form.change.html}</p>
        {/if}
    </div>
    <div id='tab1' class='tab'>
        <table class="ListTable">
            <tr class="ListHeader"><td class="FormHeader" colspan="2"><img src='./img/icones/16x16/clients.gif'>&nbsp;&nbsp;{$form.header.title}</td></tr>
            <tr class="list_lvl_1"><td class="ListColLvl1_name" colspan="2"><img src='./img/icones/16x16/clipboard.gif'>&nbsp;&nbsp;{$form.header.information}</td></tr>
            <tr class="list_one"><td class="FormRowField">{$form.rule_name.label}</td><td class="FormRowValue">{$form.rule_name.html}</td></tr>
            <tr class="list_two"><td class="FormRowField">{$form.rule_description.label}</td><td class="FormRowValue">{$form.rule_description.html}</td></tr>
            <tr class="list_lvl_1"><td class="ListColLvl1_name" colspan="2"><img src='./img/icones/16x16/link.gif'>&nbsp;&nbsp;{$form.header.relation}</td></tr>
            <tr class="list_one"><td class="FormRowField">{$form.instance_id.label}</td><td class="FormRowValue">{$form.instance_id.html}</td></tr>
            <tr class="list_two"><td class="FormRowField">{$form.host_template_id.label}</td><td class="FormRowValue">{$form.host_template_id.html}</td></tr>
            <tr class="list_one"><td class="FormRowField">{$form.hostgroups.label}</td><td class="FormRowValue"><p  class="oreonbutton">{$form.hostgroups.html}</p></td></tr>
            <tr class="list_two"><td class="FormRowField">{$form.hostcategories.label}</td><td class="FormRowValue"><p  class="oreonbutton">{$form.hostcategories.html}</p></td></tr>
            <tr class="list_one"><td class="FormRowField">{$form.ip_range.label}</td><td class="FormRowValue">{$form.ip_range.html}</td></tr>
            <tr class="list_lvl_1"><td class="ListColLvl1_name" colspan="2"><img src='./modules/centreon-glpi/img/glpi.gif'>&nbsp;&nbsp;{$form.header.rules_header}</td></tr>
            <tr class="list_one"><td class="FormRowField">{$form.dropdowns.name.label}</td><td class="FormRowValue">{$form.dropdowns.name.html}</td></tr>
            <tr class="list_two"><td class="FormRowField">{$form.dropdowns.locations_id.label}</td><td class="FormRowValue">{$form.dropdowns.locations_id.html}</td></tr>
            <tr class="list_one"><td class="FormRowField">{$form.dropdowns.users_id_tech.label}</td><td class="FormRowValue">{$form.dropdowns.users_id_tech.html}</td></tr>
            <tr class="list_two"><td class="FormRowField">{$form.dropdowns.groups_id_tech.label}</td><td class="FormRowValue">{$form.dropdowns.groups_id_tech.html}</td></tr>
            <tr class="list_one"><td class="FormRowField">{$form.dropdowns.users_id.label}</td><td class="FormRowValue">{$form.dropdowns.users_id.html}</td></tr>
            <tr class="list_two"><td class="FormRowField">{$form.dropdowns.groups_id.label}</td><td class="FormRowValue">{$form.dropdowns.groups_id.html}</td></tr>
            <tr class="list_one"><td class="FormRowField">{$form.dropdowns.network_id.label}</td><td class="FormRowValue">{$form.dropdowns.network_id.html}</td></tr>
            <!-- <tr class="list_two"><td class="FormRowField">{$form.dropdowns.networkequipmenttypes_id.label}</td><td class="FormRowValue">{$form.dropdowns.networkequipmenttypes_id.html}</td></tr> -->
            <!-- <tr class="list_one"><td class="FormRowField">{$form.dropdowns.networkequipmentmodels_id.label}</td><td class="FormRowValue">{$form.dropdowns.networkequipmentmodels_id.html}</td></tr> -->
            <tr class="list_two"><td class="FormRowField">{$form.dropdowns.states_id.label}</td><td class="FormRowValue">{$form.dropdowns.states_id.html}</td></tr>
            <tr class="list_one"><td class="FormRowField">{$form.dropdowns.domains_id.label}</td><td class="FormRowValue">{$form.dropdowns.domains_id.html}</td></tr>
            <tr class="list_two"><td class="FormRowField">{$form.dropdowns.manufacturers_id.label}</td><td class="FormRowValue">{$form.dropdowns.manufacturers_id.html}</td></tr>
            {if $o == "a" || $o == "c"}
                <tr class="list_lvl_2"><td class="ListColLvl2_name" colspan="2">{$form.required._note}</td></tr>
            {/if}
        </table>
    </div>
    <div id="validForm">
        {if $o == "a" || $o == "c"}
            <p>{$form.action.html}</p>
            <p class="oreonbutton">{$form.submitC.html}{$form.submitA.html}&nbsp;&nbsp;&nbsp;{$form.reset.html}</p>
        {else if $o == "w"}
            <p class="oreonbutton">{$form.change.html}</p>
        {/if}
    </div>
    {$form.hidden}
</form>