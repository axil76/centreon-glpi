<script type="text/javascript" src="./include/common/javascript/tool.js"></script>
<form name='form' method='POST'>
    <table class="ToolbarTable">
        <tr class="ToolbarTR">			
            <td class="Toolbar_TDSelectAction_Top">
                {$msg.options} {$form.o1.html}
                &nbsp;&nbsp;&nbsp;
                <a href="{$msg.addL}">{$msg.addT}</a>
            </td>
        <input name="p" value="{$p}" type="hidden">
        {php}
			   include('./include/common/pagination.php');
        {/php}
        </tr>
    </table>
    <table class="ListTable">
        <tr class="ListHeader">
            <td class="ListColHeaderPicker"><input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"/></td>
            <td class="ListColHeaderLeft">&nbsp;{$headerMenu_name}</td>
            <td class="ListColHeaderLeft">&nbsp;{$headerMenu_desc}</td>
            <td class="ListColHeaderCenter">&nbsp;{$headerMenu_status}</td>
            <td class="ListColHeaderRight">&nbsp;{$headerMenu_options}</td>
        </tr>
        {section name=elem loop=$elemArr}
            <tr class="{cycle values="list_one,list_two"}">            
                <td class="ListColPicker">{$elemArr[elem].RowMenu_select}</td>
                <td class="ListColLeft"><a href="{$elemArr[elem].RowMenu_link}">{$elemArr[elem].RowMenu_name}</a></td>
                <td class="ListColLeft"><a href="{$elemArr[elem].RowMenu_link}">{$elemArr[elem].RowMenu_desc}</a></td>
                <td class="ListColCenter">{$elemArr[elem].RowMenu_status}</td>
                <td class="ListColRight" align="right">{$elemArr[elem].RowMenu_options}</td>
            </tr>
        {/section}	
    </table>
    <table class="ToolbarTable">
        <tr>			
            <td class="Toolbar_TDSelectAction_Bottom">
                {$msg.options} {$form.o2.html}
                &nbsp;&nbsp;&nbsp;
                <a href="{$msg.addL}">{$msg.addT}</a>
            </td>		
        <input name="p" value="{$p}" type="hidden">
        {php}
			   include('./include/common/pagination.php');
        {/php}
        </tr>
    </table>
    <input type='hidden' name='o' id='o' value='42'>
    <input type='hidden' id='limit' name='limit' value='{$limit}'>	
    {$form.hidden}
</form>
