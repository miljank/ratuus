<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<script type="text/javascript" language="JavaScript">
{literal}
function send_data() {
	admin = document.forms['admin_form'].admin;
	old_value = window.opener.document.getElementById("domain_admins").value;

	if(old_value) {
		new_value = old_value+', '+admin.options[admin.selectedIndex].value;
	} else {
		new_value = admin.options[admin.selectedIndex].value;
	}

        window.opener.document.getElementById("domain_admins").value = new_value;
	self.close();
}
{/literal}
</script>
<link rel="stylesheet" href="r.css" type="text/css" media="screen" />
</head>
<body id="popup">
<div id="pcont">
{if $domains}
	<form action="{$link.add_domain_admins}" method="post" name="domain_form" onChange="document.domain_form.submit();">
		<select name="domain" style="float:left">
			<option value="">-- Select domain --</option>
			{section name=id loop=$domains}
			{strip}
				<option value="{$domains[id].domain}" {if $domains[id].domain eq $domain}selected{/if}>{$domains[id].domain}</option>
			{/strip}
			{/section}
		</select>
	</form>
{/if}
<br />
{if $users}
	<form name="admin_form">
		<select name="admin" style="float:left">
			{section name=id loop=$users}
			{strip}
				<option value="{$users[id].username}">{$users[id].username}</option>
			{/strip}
			{/section}
		</select>
	</form>
	<br />
	<div class="pcontbutton"><a href="javascript:send_data()">Add domain admin</a></div>
{/if}
</div>
</body>
</html>
