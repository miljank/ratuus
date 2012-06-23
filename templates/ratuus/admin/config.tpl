{include file='admin/header.tpl'}

	<div id="content"><div class="cbgt"></div>
		<div class="cbgm">


{if $list eq 'config'}
	{foreach key=option item=value from=$config}
		<div class="cc">
			<div class="ctitle">
				<span class="ed">
				{*	<a href="{$link.change_config}{$option}"><img src="templates/ratuus/img/edit.png"></a> *}
				</span>
				<span class="config" id="config_opt">{$conf.$option}</span>
				<span class="info" id="{$option}">{$value}</span>
			</div>
		</div>
	{/foreach}
	</div><div class="cbgb"></div>
	</div>
	<div id="leftbar">
		<a href="{$link.list_configs}" class="active">List Settings</a>
	</div>
{/if}

{if $form eq 'addConfig'}
<form id="myForm" action="{$link.list_config}" method="post" name="myForm">
	<table>
		<tr>
			<td>Option: </td><td><input type="text" name="config_opt" value="" /></td>
		</tr>
		<tr>
			<td>Value: </td><td><input type="text" name="config_value" value="" /></td>
		</tr>
		<tr>
			<td><input type="submit" name="button" value="Add" /></td>
		</tr>
	</table>
		<input type="hidden" name="add" value="config" />
</form>
{/if}

{if $form eq 'changeConfig'}
	{foreach key=option item=value from=$config} 
<form id="myForm" action="{$link.list_config}" method="post" name="myForm">
	<table>
		<tr>
			<td>Option: </td><td><input type="text" name="config_opt" value="{$option}" /></td>
		</tr>
		<tr>
			 <td>Value: </td><td><input type="text" name="config_value" value="{$value}" /></td>
		</tr>
		<tr>
			<td><input type="submit" name="button" value="Change" /></td>
		</tr>
	</table>
	<input type="hidden" name="change" value="config" />
</form>
	{/foreach} 
{/if}

{if $message.error}
	{$message.error}
{/if}

{if $message.info}
	{$message.info}
{/if}

{include file="admin/footer.tpl"}
