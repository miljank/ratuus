{include file='admin/header.tpl'}
	<div id="content">
		<div class="cbgt"></div>
		<div class="cbgm">
		<div id="w1">
			<span class="section">
				<a href="{$link.list_domains}">Domains</a>
			</span>
			<p>
				Total of <b>{$stats.domains}</b> domains<br />
				with <b>{$stats.users}</b> users<br />
				and <b>{$stats.aliases}</b> aliases.
			</p>
			<p>
				Total quota: <b>{$stats.quota}</b>
			</p>
			<p>
				<a href="{$link.add_domain}">Add</a> a new domain?
			</p>
		</div>
		<div id="w3">
			<span class="section">
				<a href="{$link.list_admins}">Admins</a>
			</span>
			<p>
				Total of <b>{$stats.admins}</b> admins
			</p>
			<p>
				<a href="{$link.add_admin}">Add</a> another admin?
			</p>
		</div>
		<div id="w2">
			<span class="section">
				<a href="{$link.list_config}">Configuration</a>
			</span>
			<p>
				Configuration is easy
			</p>
			<p>
				<a href="{$link.list_config}">Modify</a> it now?
			</p>
		</div>
		<div id="w4">
			<span class="section">
				<a href="{$link.list_logs}">Logs</a>
			</span>
			<p>
				<b>{$stats.logs}</b> log entry
			</p>
			<p>
				<a href="{$link.delete_log}" class="warnlink confirm" name="logs">Delete</a> them all?
			</p>
		</div>

	</div>
	<div class="cbgb"></div>
</div>
<div id="leftbar">
	<div align="center">Welcome to Ratuus!</div>
</div>
{include file='admin/footer.tpl'}
