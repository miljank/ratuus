{include file='admin/header.tpl'}

<div id="content"><div class="cbgt"></div>
	<div class="cbgm">

{* if $list eq 'logs' *}
		<div class="base-container base-layer">

			<div class="table-head">
				<div class="left-layer11">
					<span class="colhdr">
				        	Username
  					</span>
				</div>
				<div class="left-layer11">
					<span class="colhdr">
						Time
					</span>
				</div>
				<div class="left-layer11">
					<span class="colhdr">
						Domain
					</span>
				</div>
				<div class="left-layer11">
					<span class="colhdr">
						Action
					</span>
				</div>
				<div class="left-layer11">
					<span class="colhdr">
						Data
					</span>
				</div>
				<div class="space-line"></div>
			</div>

	{if $list eq 'logs'}
		{section name=id loop=$log}
		{strip}
			<div class="table-row">
				<div class="left-layer11">
					<span class="colhdr">
						{$log[id].username}
					</span>
				</div>
				<div class="left-layer11">
					<span class="colhdr">
						{$log[id].timestamp}
					</span>
				</div>
				<div class="left-layer11">
					<span class="colhdr">
						{$log[id].domain}
					</span>
				</div>
				<div class="left-layer11">
					<span class="colhdr">
						{$log[id].action}
					</span>
				</div>
				<div class="left-layer11">
					<span class="colhdr">
						{$log[id].data}
					</span>
				</div>
				<div class="space-line"></div>
			</div>

		{/strip}
		{/section}

		</div>
	</div>
	<div class="cbgb"></div>
	<p class="pagenav">{$log.0.pages} <span>&hellip;</span> <a class="number">{$log_no}</a></p>
	</div>
	{else}
			{if $text_holder}
				{$text_holder}
			{/if}
		</div>
	</div>
	<div class="cbgb"></div>
	</div>
	{/if}
	<div id="leftbar">
		<a href="{$link.list_logs}" class="active">
			Read Logs
		</a> 
		<a href="{$link.delete_log}" class="confirm" name="logs">
			Delete Logs
		</a>
	</div>

{* /if *}

{include file='admin/footer.tpl'}
