{include file='domain_admin/header.tpl'}
	<div id="content">
		<div class="cbgt"></div>
		<div class="cbgm">
		<div id="w1">
			<span class="section">
				<a href="{$link.list_domains}">Domains</a>
			</span>
			<p>
				Total of <b>{$stats.domains}</b> domains<br />
				and <b>{$stats.users}</b> users<br />
				with <b>{$stats.aliases}</b> aliases.
			</p>
			<p>
				Total quota: <b>{$stats.quota}</b>
			</p>
		</div>
		<div id="w4">
			<span class="section">
				<a href="{$link.list_logs}">Logs</a>
			</span>
			<p>
				<b>{$stats.logs}</b> log entries
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
{include file='domain_admin/footer.tpl'}
