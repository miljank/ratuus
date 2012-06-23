{include file='admin/header.tpl'}

	<div id="content"><div class="cbgt"></div>
		<div class="cbgm">

{if $list eq 'users'}
	{section name=id loop=$users}
	{strip}

	{if $users[id].active eq "0"}
		{assign var='class' value='notactive'}
	{elseif $users[id].active eq "1"}
		{assign var='class' value='active'}
	{/if}

		<div class="ac">
			<div class="title_short">
				<span class="ed">
					<a href="{$link.modify_user}{$users[id].username}">
						<img src="templates/ratuus/img/edit.png">
					</a>
					<a href="{$link.delete_user}{$users[id].username}" class="confirm" name="user {$users[id].username}">
						<img src="templates/ratuus/img/delete.png">
					</a>
				</span>
				<span class="{$class}">{$users[id].username}</span>
			</div>
			<p class="created2">
				Quota: <b>{$users[id].nice_quota.value} {$users[id].nice_quota.quota}</b> {if $users[id].name ne ''}| Name: <b>{$users[id].name}</b>{/if}<br />
				Created: {$users[id].created} | Modified: {$users[id].modified}
			</p>
		</div>
	{/strip}
	{/section}
	</div>
		<div class="cbgb"></div>
		<p class="pagenav">
			{$users.0.pages} 
			<span>&hellip;</span> 
			<a class="number">{$users.0.no}</a>
		</p>
	</div>
	<div id="leftbar">
	{if $search}
		<a href="#">Add User</a>
		<a href="#" class="active">List Users</a>
		<a href="#">Add Alias</a>
		<a href="#">List Aliases</a>
	{else}
		<a href="{$link.add_user}{$users.0.domain}">Add User</a> 
		<a href="{$link.list_users}{$users.0.domain}" class="active">List Users</a>
		<a href="{$link.add_alias}{$users.0.domain}">Add Alias</a>
		<a href="{$link.list_aliases}{$users.0.domain}">List Aliases</a>
	{/if}
	</div>

{elseif $form eq 'addUser'}
			<form id="myForm" action="{$link.list_users}{$domain}" method="post" name="myForm">
				<dl>
					<dt>
						<label for="domain">Username:</label>
					</dt>
					<dd>
						<input name="username" id="username" class="rounded required" style="float:left" type="text" /> @{$domain}
						<span class="hint">This is the username. Eg. "john.doe".<span class="hint-pointer">&nbsp;</span></span>
					</dd>
					<dt>
						<label for="name">Name:</label>
					</dt>
					<dd>
						<input name="name" id="name" class="rounded" type="text" />
						<span class="hint">Name of this user, so you can later recognize it easily. Eg. "Jonhn Doe" or "John's email".<span class="hint-pointer">&nbsp;</span></span>
					</dd>
					<dt>
						<label for="password">Password:</label>
					</dt>
					<dd>
						<input name="password" id="password" class="rounded required" type="password" />
						<span class="hint">Choose strong passwords, at least 8 characters long.<span class="hint-pointer">&nbsp;</span></span>
					</dd>
					<dt>
						<label for="password_2">Password:</label>
					</dt>
					<dd>
						<input name="password_2" id="password_2" class="rounded required password" type="password" />
						<span class="hint">Repeat password for verification.<span class="hint-pointer">&nbsp;</span></span>
					</dd>
					<dt>
						<label for="quota_value">Quota:</label>
					</dt>
					<dd>
						<input name="quota_value" id="quota_value" class="rounded required numeric" style="float:left" type="text" value="{$quota_value}" />
						<select name="quota" style="float:left">
							<option value="1" {if $quota eq "KB"} selected {/if}>KB</option>
							<option value="1000" {if $quota eq "MB"} selected {/if}>MB</option>
							<option value="1000000" {if $quota eq "GB"} selected {/if}>GB</option>
						</select>
						<span class="hint">Space devoted to this user.<span class="hint-pointer">&nbsp;</span></span>
					</dd>
					<dt>
						<label for="active">Active:</label>
					</dt>
					<dd>
						<select name="active">
							<option value="no">No</option>
							<option value="yes" selected>Yes</option>
						</select>
						<span class="hint">Will it be active?<span class="hint-pointer">&nbsp;</span></span>
					</dd>
					<dt class="button">&nbsp;</dt>
					<dd class="button">
						<input type="submit"    class="button" value="Submit" />
						<input type="hidden" name="add" value="user" />
					</dd>
				</dl>
			</form>
		</div><div class="cbgb"></div>
	</div>
	<div id="leftbar">
		<a href="{$link.add_user}{$domain}" class="active">Add User</a> 
		<a href="{$link.list_users}{$domain}">List Users</a>
		<a href="{$link.add_alias}{$domain}">Add Alias</a>
		<a href="{$link.list_aliases}{$domain}">List Aliases</a>
	</div>

{elseif $form eq 'changeUser'}
			<form id="myForm" action="{$link.list_users}{$user.0.domain}" method="post" name="myForm">
				<dl>
					<dt>
						<label for="domain">Username:</label>
					</dt>
					<dd>
						<input name="username" id="username" class="rounded required" style="float:left" type="text" value="{$user.0.username}" disabled />
						<span class="hint">This is the username. Eg. "john.doe".<span class="hint-pointer">&nbsp;</span></span>
					</dd>
					<dt>
						<label for="name">Name:</label>
					</dt>
					<dd>
						<input name="name" id="name" class="rounded" type="text" value="{$user.0.name}" />
						<span class="hint">Name of this user, so you can later recognize it easily. Eg. "Jonhn Doe" or "John's email".<span class="hint-pointer">&nbsp;</span></span>
					</dd>
					<dt>
						<label for="password">Password:</label>
					</dt>
					<dd>
						<input name="password" id="password" class="rounded" type="password" />
						<span class="hint">Choose strong passwords, at least 8 characters long.<span class="hint-pointer">&nbsp;</span></span>
					</dd>
					<dt>
						<label for="password1">Password:</label>
					</dt>
					<dd>
						<input name="password1" id="password1" class="rounded password" type="password" />
						<span class="hint">Repeat password for verification.<span class="hint-pointer">&nbsp;</span></span>
					</dd>
					<dt>
						<label for="quota_value">Quota:</label>
					</dt>
					<dd>
						<input name="quota_value" id="quota_value" class="rounded required numeric" style="float:left" type="text" value="{$user.0.nice_quota.value}" />
						<select name="quota" style="float:left">
							<option value="1" {if $user.0.nice_quota.quota eq "KB"} selected {/if}>KB</option>
							<option value="1000" {if $user.0.nice_quota.quota eq "MB"} selected {/if}>MB</option>
							<option value="1000000" {if $user.0.nice_quota.quota eq "GB"} selected {/if}>GB</option>
						</select>
						<span class="hint">Space devoted to this user.<span class="hint-pointer">&nbsp;</span></span>
					</dd>
					<dt>
						<label for="active">Active:</label>
					</dt>
					<dd>
						<select name="active">
							<option value="no" {if $user.0.active eq 0} "selected" {/if}>No</option>
							<option value="yes" {if $user.0.active eq 1} "selected" {/if}>Yes</option>
						</select>
						<span class="hint">Will it be active?<span class="hint-pointer">&nbsp;</span></span>
					</dd>
					<dt class="button">&nbsp;</dt>
					<dd class="button">
						<input type="submit"    class="button" value="Submit" />
						<input type="hidden" name="change" value="user" />
						<input type="hidden" name="username" value="{$user.0.username}" />
					</dd>
				</dl>
			</form>
		</div><div class="cbgb"></div>
	</div>
	<div id="leftbar">
		<a href="{$link.add_user}{$user.0.domain}">Add User</a> 
		<a href="{$link.list_users}{$user.0.domain}">List Users</a>
		<a href="{$link.add_alias}{$user.0.domain}">Add Alias</a>
		<a href="{$link.list_aliases}{$user.0.domain}">List Aliases</a>
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
		<a href="{$link.list_users}{$domain}" class="active">List Users</a>
		<a href="{$link.add_alias}{$domain}">Add Alias</a>
		<a href="{$link.list_aliases}{$domain}">List Aliases</a>
	</div>
{/if}

{include file='admin/footer.tpl'}
