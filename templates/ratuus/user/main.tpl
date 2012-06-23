{include file='user/header.tpl'}
	{if $stats.name ne ''}
		{assign var='username' value=$stats.name}
	{else}
		{assign var='username' value=$stats.username}
	{/if}
	<div id="content">
		<div class="cbgt"></div>
		<div class="cbgm">
		<div id="w5">
			<span class="section">
				<b><u>{$username}</u></b>
			</span>
			<p>
				Hello {$username}.
			</p>
			<p>
				Your quota is set to <b>{$stats.quota}</b>.
			</p>
			<p>
				Your account was created <b>{$stats.created}</b>.
				and modified on <b>{$stats.modified}</b>.
			</p>
			<p>
				<a href="{$link.change_password}">Modify</a> your account.
		</div>
		<div id="w4">
			<span class="section">
				<a href="{$link.list_logs}">Logs</a>
			</span>
			<p>
				<b>{$stats.logs}</b> log entries
			</p>
		</div>

	</div>
	<div class="cbgb"></div>
</div>
<div id="leftbar">
	<div align="center">Welcome to Ratuus!</div>
</div>
{include file='user/footer.tpl'}
