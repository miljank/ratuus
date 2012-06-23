{include file='admin/header.tpl'}

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
					<a href="{$link.modify_domain}{$domains[id].domain}">
						<img src="templates/ratuus/img/change-domain.png" width="124" height="22">
					</a> 
					{* <a href="{$link.delete_domain}{$domains[id].domain}"> *}
					<a href="{$link.delete_domain}{$domains[id].domain}" class="confirm" name="domain {$domains[id].domain}">
						<img src="templates/ratuus/img/delete-domain.png" width="116" height="22">
					</a>
				</div>
			</div>
	{/strip}
	{/section}
		</div><div class="cbgb"></div>
		<p class="pagenav">{$domains.0.pages} <span>&hellip;</span> <a class="number">{$domains.0.no}</a></p>
	</div>
	<div id="leftbar">
		<a href="{$link.add_domain}">Add Domain</a> 
		<a href="{$link.list_domains}" class="active">List Domains</a>
	</div>

{elseif $form eq "addDomain"}
                	<form id="myForm" action="{$link.list_domains}" method="post" name="myForm">
				<dl>
					<dt>
						<label for="domain">Domain:</label>
					</dt>
					<dd>
						<input name="domain" id="domain" class="rounded required" type="text" />
						<span class="hint">This is the domain name. Eg. "domain.com".<span class="hint-pointer">&nbsp;</span></span>
					</dd>
					<dt>
						<label for="description">Description:</label>
					</dt>
					<dd>
						<input name="description" id="description" class="rounded" type="text" />
						<span class="hint">Description for this domain, so you can later recognize it easily.<span class="hint-pointer">&nbsp;</span></span>
					</dd>
					<dt>
						<label for="mailboxes">Mailboxes:</label>
					</dt>
					<dd>
						<input name="mailboxes" id="mailboxes" class="rounded required numeric" type="text" value={$mailboxes} />
						<span class="hint">Number of mailboxes you want on this domain.<span class="hint-pointer">&nbsp;</span></span>
					</dd>
					<dt>
						<label for="aliases">Aliases:</label>
					</dt>
					<dd>
						<input name="aliases" id="aliases" class="rounded required numeric" type="text" value={$aliases} />
						<span class="hint">Should there be any aliases/redirections? If so, how many?.<span class="hint-pointer">&nbsp;</span></span>
					</dd>
					<dt>
						<label for="quota_value">Quota:</label>
					</dt>
					<dd>
						<input name="quota_value" id="quota_value" class="rounded required numeric" style="float:left" type="text" value="{$quota_value}" />
						<select name="quota" style="float:left">
							<option value="1" {if $quota eq "KB"} selected {/if}>KB</option>
							<option value="1000" {if $quota eq "MB"} selected {/if}>MB</option>
							<option value="1000000"  {if $quota eq "GB"} selected {/if}>GB</option>
						</select>
						<span class="hint">Space devoted to this domain.<span class="hint-pointer">&nbsp;</span></span>
					</dd>
					<dt>
						<label for="transport">Transport:</label>
					</dt>
					<dd>
						<input type="text" name="transport" id="transport" class="rounded" value="virtual:" disabled  />
						<input type="hidden" name="transport" value="virtual:" />
						<span class="hint">disabled.<span class="hint-pointer">&nbsp;</span></span>
					</dd>
					<dt>
						<label for="domain_admins">Domain admins:</label>
					</dt>
					<dd>
						<textarea name="domain_admins" id="domain_admins" class="rounded"></textarea>
						<a href="#" onClick="javascript:popup('{$link.add_domain_admins}');">Add</a>
						<span class="hint">Who should be administrator for this domain. It can be more than one person.<span class="hint-pointer">&nbsp;</span></span>
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
						<input type="submit"	class="button" value="Submit" />
						<input type="hidden" name="add" value="domain" />
					</dd>
				</dl>
			</form>
		</div><div class="cbgb"></div>
	</div>
	<div id="leftbar"><a href="{$link.add_domain}" class="active">Add Domain</a> <a href="{$link.list_domains}">List Domains</a></div>

{elseif $form eq "changeDomain"}
                <form id="myForm" action="{$link.list_domains}" method="post" name="myForm">
			<dl>
				<dt>
					<label for="domain">Domain:</label>
				</dt>
				<dd>
					<input name="domain" id="domain" class="rounded required" type="text" value="{$domain.0.domain}" disabled />
					<span class="hint">
						This is the domain name. Eg. "domain.com".
						<span class="hint-pointer">&nbsp;</span>
					</span>
				</dd>
				<dt>
					<label for="description">Description:</label>
				</dt>
				<dd>
					<input name="description" id="description" class="rounded" type="text" value="{$domain.0.description}" />
					<span class="hint">
						Description for this domain, so you can later recognize it easily.
						<span class="hint-pointer">&nbsp;</span>
					</span>
				</dd>
				<dt>
					<label for="mailboxes">Mailboxes:</label>
				</dt>
				<dd>
					<input name="mailboxes" id="mailboxes" class="rounded required numeric" type="text" value="{$domain.0.mailboxes}" />
					<span class="hint">
						Number of mailboxes you want on this domain.
						<span class="hint-pointer">&nbsp;</span>
					</span>
				</dd>
				<dt>
					<label for="aliases">Aliases:</label>
				</dt>
				<dd>
					<input name="aliases" id="aliases" class="rounded required numeric" type="text" value="{$domain.0.aliases}" />
					<span class="hint">
						Should there be any aliases/redirections? If so, how many?
						<span class="hint-pointer">&nbsp;</span>
					</span>
				</dd>
				<dt>
					<label for="quota_value">Quota:</label>
				</dt>
				<dd>
					<input name="quota_value" id="quota_value" class="rounded required numeric" style="float:left" type="text" value="{$domain.0.nice_quota.value}" />
					<select name="quota" style="float:left">
						<option value="1" {if $domain.0.nice_quota.quota eq "KB"} selected {/if}>KB</option>
						<option value="1000" {if $domain.0.nice_quota.quota eq "MB"} selected {/if}>MB</option>
						<option value="1000000"  {if $domain.0.nice_quota.quota eq "GB"} selected {/if}>GB</option>
					</select>
					<span class="hint">
						Space devoted to this domain.
						<span class="hint-pointer">&nbsp;</span>
					</span>
				</dd>
				<dt>
					<label for="transport">Transport:</label>
				</dt>
				<dd>
					<input type="text" name="transport" id="transport" class="rounded" value="{$domain.0.transport}" disabled  />
					<span class="hint">disabled.<span class="hint-pointer">&nbsp;</span></span>
				</dd>
				<dt>
					<label for="domain_admins">Domain admins:</label>
				</dt>
				<dd>
					<textarea name="domain_admins" id="domain_admins" class="rounded">{$domain.0.admin}</textarea>
					<a href="javascript:popup('/index.php?form=addDomainAdmin');">Add</a>
					<span class="hint">Who should be administrator for this domain. It can be more than one person.<span class="hint-pointer">&nbsp;</span></span>
				</dd>
				<dt>
					<label for="active">Active:</label>
				</dt>
				<dd>
					<select name="active">
						<option value="no" {if $domain.0.active eq 0} "selected" {/if}>No</option>
						<option value="yes" {if $domain.0.active eq 1} "selected" {/if}>Yes</option>
					</select>
					<span class="hint">Will it be active?<span class="hint-pointer">&nbsp;</span></span>
				</dd>
				<dt class="button">&nbsp;</dt>
				<dd class="button">
					<input type="submit"    class="button" value="Submit" />
					<input type="hidden" name="change" value="domain" />
					<input type="hidden" name="domain" value="{$domain.0.domain}" />
					<input type="hidden" name="transport" value="{$domain.0.transport}" />
				</dd>
			</dl>

		</form>
	</div><div class="cbgb"></div>
	</div>
	<div id="leftbar"><a href="{$link.add_domain}">Add Domain</a> <a href="{$link.list_domains}">List Domains</a></div>
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

{include file='admin/footer.tpl'}
