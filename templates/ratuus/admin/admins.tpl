{include file='admin/header.tpl'}

	<div id="content"><div class="cbgt"></div>
		<div class="cbgm">

{if $form eq 'addAdmin'}
	<form id='myForm' action='{$link.list_admins}' method='post' name='myForm'>
		<dl>
			<dt>
				<label for="admin">Admin:</label>
			</dt>
			<dd>
				<input name="username" id="username" class="rounded required" type="text" />
				<span class="hint">Admin's username. Eg. "admin".<span class="hint-pointer">&nbsp;</span></span>
			</dd>
			<dt>
				<label for="password">Password:</label>
			</dt>
			<dd>
				<input name="password" id="password" class="rounded required" type="password" />
				<span class="hint">Choose strong passwords, at least 8 characters long.<span class="hint-pointer">&nbsp;</span></span>
			</dd>
			<dt>
				<label for="password1">Password:</label>
			</dt>
			<dd>
				<input name="password1" id="password1" class="rounded required password" type="password" />
				<span class="hint">Repeat password for verification.<span class="hint-pointer">&nbsp;</span></span>
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
				<input type="hidden" name="add" value="admin" />
			</dd>
		</dl>
	</form>
		</div>
		<div class="cbgb"></div>
	</div>
	<div id="leftbar">
		<a href="{$link.add_admin}" class="active">Add Admin</a> 
		<a href="{$link.list_admins}">List Admins</a>
	</div>

{elseif $form eq 'changeAdmin'}
	<form id='myForm' action='{$link.list_admins}' method='post' name='myForm'>
		<dl>
			<dt>
				<label for="admin">Admin:</label>
			</dt>
			<dd>
				<input name="username" id="username" class="rounded required" type="text" value="{$admin.username}" disabled />
			</dd>
			<dt>
				<label for="password">Password:</label>
			</dt>
			<dd>
				<input name="password" id="password" class="rounded required" type="password" />
				<span class="hint">Choose strong passwords, at least 8 characters long.<span class="hint-pointer">&nbsp;</span></span>
			</dd>
			<dt>
				<label for="password1">Password:</label>
			</dt>
			<dd>
				<input name="password1" id="password1" class="rounded required password" type="password" />
				<span class="hint">Repeat password for verification.<span class="hint-pointer">&nbsp;</span></span>
			</dd>
			<dt>
				<label for="active">Active:</label>
			</dt>
			<dd>
				<select name="active">
					<option value="no" {if $admin.active eq 0} "selected" {/if}>No</option>
					<option value="yes" {if $admin.active eq 1} "selected" {/if}>Yes</option>
				</select>
			</dd>
			<dt class="button">&nbsp;</dt>
			<dd class="button">
				<input type="submit"    class="button" value="Submit" />
				<input type="hidden" name="change" value="admin" />
				<input type="hidden" name="username" value="{$admin.username}" />
			</dd>
		</dl>
	</form>
		</div>
		<div class="cbgb"></div>
	</div>
	<div id="leftbar">
		<a href="{$link.add_admin}">Add Admin</a> 
		<a href="{$link.list_admins}">List Admins</a>
	</div>

{elseif $list eq 'admins'}
	{section name=id loop=$admins}
	{strip}
		{if $admins[id].active eq "0"}
			{assign var='active' value='No'}
			{assign var='status' value='Not Active'}
			{assign var='class' value='notactive'}
		{elseif $admins[id].active eq "1"}
			{assign var='active' value='Yes'}
			{assign var='status' value='Active'}
			{assign var='class' value='active'}
		{/if}

		<div class="ac">
			<div class="title_short">
				<span class="ed">
					<a href="{$link.modify_admin}{$admins[id].username}">
						<img src="templates/ratuus/img/edit.png">
					</a> 
					<a href="{$link.delete_admin}{$admins[id].username}" class="confirm" name="admin user {$admins[id].username}">
						<img src="templates/ratuus/img/delete.png">
					</a>
				</span>
				<span class="{$class}">
					{$admins[id].username}
				</span>
			</div>
			<p class="created2"><b>{$status}</b><br />
			Created: {$admins[id].created} | Modified: {$admins[id].modified}</p>
		</div>

	{/strip}
	{/section}
		{if $message.error}
			{$message.error}
		{/if}

		{if $message.info}
			{$message.info}
		{/if}
		</div>
		<div class="cbgb"></div>
		<p class="pagenav">
			{$admins.0.pages} 
			<span>&hellip;</span> 
			<a class="number">{$admin_no}</a>
		</p>
	</div>
	<div id="leftbar">
		<a href="{$link.add_admin}">Add Admin</a> 
		<a href="{$link.list_admins}" class="active">List Admins</a>
	</div>
{else}
	{if $message.error}
		{$message.error}
	{/if}

	{if $message.info}
		{$message.info}
	{/if}

		</div>
		<div class="cbgb"></div>
	</div>
	<div id="leftbar">
		<a href="{$link.add_admin}">Add Admin</a> 
		<a href="{$link.list_admins}">List Admins</a>
	</div>
{/if}

{include file='admin/footer.tpl'}
