{include file='domain_admin/header.tpl'}

	<div id="content"><div class="cbgt"></div>
		<div class="cbgm">

{if $list eq 'domains'}
	{section name=id loop=$domains}
	{strip}

		{if $domains[id].active eq "0"}
			{assign var='class' value='notactive'}
		{elseif $domains[id].active eq "1"}
			{assign var='class' value='active'}
		{/if}
			<div class="tc">

			<div class="title">
				<span class="mboxs">Mailboxes: {$domains[id].used_mailboxes}/{$domains[id].mailboxes}</span><span class="{$class}">{$domains[id].domain}</span>
			</div>
			<div class="tab">
				{if $domains[id].description ne ''}
					Description: {$domains[id].description}<br />
				{/if}
					Aliases: {$domains[id].used_aliases}/{$domains[id].aliases} &nbsp;&nbsp;&nbsp; 
					Mailboxes: {$domains[id].used_mailboxes}/{$domains[id].mailboxes} &nbsp;&nbsp;&nbsp; 
					Quota: {$domains[id].nice_used_quota.value} {$domains[id].nice_used_quota.quota}/{$domains[id].nice_quota.value} {$domains[id].nice_quota.quota}<br/>
					
					{if $domains[id].admin}
					Admins: {$domains[id].admin} <br/> 
					{/if}
					{* Transport: {$domains[id].transport} Backupmx: {$domains[id].backupmx}<br/> *}
					<span class="created">Created: {$domains[id].created} | Modified: {$domains[id].modified}</span>
					<br/><br/>
					<a href="{$link.list_users}{$domains[id].domain}">
						<img src="templates/ratuus/img/users.png" width="62" height="22">
					</a> 
					<a href="{$link.add_user}{$domains[id].domain}">
						<img src="templates/ratuus/img/add-user.png" width="83" height="22">
					</a> 
					<a href="{$link.list_aliases}{$domains[id].domain}">
						<img src="templates/ratuus/img/aliases.png" width="70" height="22">
					</a> 
					<a href="{$link.add_alias}{$domains[id].domain}">
						<img src="templates/ratuus/img/add-alias.png" width="82" height="22">
					</a> 
				</div>
			</div>
	{/strip}
	{/section}
		</div><div class="cbgb"></div>
		<p class="pagenav">{$domains.0.pages} <span>&hellip;</span> <a class="number">{$domains.0.no}</a></p>
	</div>
	<div id="leftbar">
		<a href="{$link.list_domains}" class="active">List Domains</a>
	</div>

{else}
	{if $text_holder}
		{$text_holder}
	{/if}

		</div>
		<div class="cbgb"></div>
	</div>
	<div id="leftbar">
		<a href="{$link.add_domain}">Add Domain</a>
		<a href="{$link.list_domains}" class="active">List Domains</a>
	</div>
{/if}

{include file='domain_admin/footer.tpl'}
