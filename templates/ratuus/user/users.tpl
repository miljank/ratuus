{include file='user/header.tpl'}

	<div id="content"><div class="cbgt"></div>
		<div class="cbgm">

{if $form eq 'changeUser'}
	<form id="myForm" action="{$link.list_users}" method="post" name="myForm">
		<dl>
			<dt>
				<label for="domain">Username:</label>
			</dt>
			<dd>
				<input name="username" id="username" class="rounded" style="float:left" type="text" value="{$user.0.username}" disabled />
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
			<dt class="button">&nbsp;</dt>
				<dd class="button">
					<input type="submit"    class="button" value="Submit" />
					<input type="hidden" name="change" value="user" />
					<input type="hidden" name="username" value="{$user.0.username}" />
					<input type="hidden" name="domain" value="{$user.0.domain}" />
				</dd>
			</dl>
		</form>
	</div><div class="cbgb"></div>
	</div>
	<div id="leftbar">
		<a href="{$link.main}">Home</a>
	</div>

{else}

	{if $users_action_status}
		{$message}
	{/if}

		</div>
		<div class="cbgb"></div>
	</div>
	<div id="leftbar">
		<a href="{$link.main}">List User</a>
	</div>

{/if}

{include file='user/footer.tpl'}
