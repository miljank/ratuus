{include file='domain_admin/header.tpl'}

		<div id="content"><div class="cbgt"></div>
			<div class="cbgm">

{if $form eq 'addAlias'}
                 <form id="myForm" action="{$link.list_aliases}{$domain}" method="post" name="myForm">
			<dl>
				<dt>
					 <label for="alias">Alias:</label>
				</dt>
				<dd>
					<input name="alias" id="alias" class="rounded required" type="text" />@{$domain}
					<span class="hint">Alias you would like to create. Eg. "john.doe".<span class="hint-pointer">&nbsp;</span></span>
				</dd>
				<dt>
					<label for="destination">Destination:</label>
				</dt>
				<dd>
					<input name="destination" id="destination" class="rounded required email" type="text" />
					<span class="hint">Where you would like to redirect emails coming to this alias. Eg. "john.doe@gmail.com".<span class="hint-pointer">&nbsp;</span></span>
				</dd>
				<dt>
					<label for="active">Active:</label>
				</dt>
				<dd>
					<select name="active">
						<option value="no">No</option>
						<option value="yes" selected>Yes</option>
					</select>
				</dd>
				<dt class="button">&nbsp;</dt>
				<dd class="button">
					<input type="submit"    class="button" value="Submit" />
					<input type="hidden" name="add" value="alias" />
					<input type="hidden" name="domain" value="{$domain}" />
				</dd>
			</dl>
		</form>
	</div><div class="cbgb"></div>
	</div>
	<div id="leftbar">
		<a href="{$link.add_user}{$domain}">Add User</a>
		<a href="{$link.list_users}{$domain}">List Users</a>
		<a href="{$link.add_alias}{$domain}" class="active">Add Alias</a> 
		<a href="{$link.list_aliases}{$domain}">List Aliases</a>
	</div>

{elseif $form eq 'changeAlias'}
                 <form id="myForm" action="{$link.list_aliases}{$alias.domain}" method="post" name="myForm">
			<dl>
				<dt>
					<label for="alias">Alias:</label>
				</dt>
				<dd>
					<input name="alias" id="alias" class="rounded required" type="text" value="{$alias.address}" disabled />
					<span class="hint">Alias you would like to create. Eg. "john.doe@domain.org".<span class="hint-pointer">&nbsp;</span></span>
				<dd>
				<dt>
					<label for="destination">Destination:</label>
				</dt>
				<dd>
					<input name="destination" id="destination" class="rounded required email" type="text" value="{$alias.goto}" />
					<span class="hint">Where you would like to redirect emails coming to this alias. Eg. "john.doe@gmail.com".<span class="hint-pointer">&nbsp;</span></span>
				</dd>
				<dt>
					<label for="active">Active:</label>
				</dt>
				<dd>
					<select name="active">
						<option value="no" {if $alias.active eq 0}selected{/if}>No</option>
						<option value="yes" {if $alias.active eq 1}selected{/if}>Yes</option>
					</select>
				</dd>
				<dt class="button">&nbsp;</dt>
				<dd class="button">
					<input type="submit"    class="button" value="Submit" />
					<input type="hidden" name="change" value="alias" />
					<input type="hidden" name="domain" value="{$alias.domain}" />
					<input type="hidden" name="alias" value="{$alias.address}" />
				</dd>
			</dl>
		</form>
	</div><div class="cbgb"></div>
	</div> 
	<div id="leftbar">
		<a href="{$link.add_user}{$alias.domain}">Add User</a>
		<a href="{$link.list_users}{$alias.domain}">List Users</a>
		<a href="{$link.add_alias}{$alias.domain}">Add Alias</a> 
		<a href="{$link.list_aliases}{$alias.domain}">List Aliases</a>
	</div>

{elseif $list eq 'alias'}
	{section name=id loop=$alias}
	{strip}
		{if $alias[id].active eq 1}
			{assign var='active' value='Yes'}
			{assign var='class' value='active'}
		{elseif $alias[id].active eq 0}
			{assign var='active' value='No'}
			{assign var='class' value='notactive'}
		{/if}
 
		<div class="ac">
			<div class="title_short">
				<span class="ed">
					<a href="{$link.modify_alias}{$alias[id].address}">
						<img src="templates/ratuus/img/edit.png">
					</a>
					<a href="{$link.delete_alias}{$alias[id].address}" class="confirm" name="alias {$alias[id].address}">
						<img src="templates/ratuus/img/delete.png">
					</a>
				</span>
				<span class="{$class}">{$alias[id].address}</span>
			</div>
			<p class="created2">
				Destination: <b>{$alias[id].goto}</b><br />
				Created: {$alias[id].created} | Modified: {$alias[id].modified}</span>
			</p>
		 </div>
	{/strip}
	{/section}
	{if $text_holder}
		{$text_holder}
	{/if}

		</div><div class="cbgb"></div>
			<p class="pagenav">{$alias.0.pages}<span>&hellip;</span> <a class="number">{$alias.0.no}</a></p>
		</div>
		<div id="leftbar">
		{if $search}
			<a href="#">Add User</a>
			<a href="#">List Users</a>
			<a href="#">Add Alias</a> 
			<a href="#" class="active">List Aliases</a>
		{else}
			<a href="{$link.add_user}{$domain}">Add User</a>
			<a href="{$link.list_users}{$domain}">List Users</a>
			<a href="{$link.add_alias}{$domain}">Add Alias</a> 
			<a href="{$link.list_aliases}{$domain}" class="active">List Aliases</a>
		{/if}
		</div>
{else}
	{if $text_holder}
		{$text_holder}
	{/if}

	</div>
	<div class="cbgb"></div>
	</div>
	<div id="leftbar">
		<a href="{$link.add_user}{$domain}">Add User</a>
		<a href="{$link.list_users}{$domain}">List Users</a>
		<a href="{$link.add_alias}{$domain}">Add Alias</a>
		<a href="{$link.list_aliases}{$domain}" class="active">List Aliases</a>
	</div>
{/if}

{include file='domain_admin/footer.tpl'}
