<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Ratuus &bull; Login</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="shortcut icon" href="templates/ratuus/img/favicon3.ico" />
		<link rel="stylesheet" href="templates/ratuus/style/r.css" type="text/css" media="screen" />
	</head>

	<body id="login">
		<div id="container">
		{if $message}
			<div class="message">
				<div class="{$msg_type}">
					{$message.$msg_type}
				</div>
			</div>
		{/if}

			<div id="logo">
				<a href="#"><img src="templates/ratuus/img/logo.gif"></a>
			</div>

			<div id="main">
				<div id="content">
					<div class="cbgt"></div>
					<div class="cbgm">

						<form id="myForm" action="{$link.main}" method="post" name="myForm">
							<dl>
								<dt>
									<label for="username">Username:</label>
								</dt>
								<dd>
									<input name="username" id="username" class="rounded" type="text" />
								</dd>
								<dt>
									<label for="password">Password:</label>
								</dt>
								<dd>
									<input name="password" id="password" class="rounded" type="password" />
								</dd>
								<dt class="button">&nbsp;</dt>
								<dd class="button">
									<input type="submit" name="login" class="button" value="Login" />
								</dd>
							</dl>
						</form>
						{if $text_holder}
							{$text_holder}
						{/if}
					</div>
					<div class="cbgb"></div>
				</div>
				<div id="leftbar">
					<a href="#" class="active">Login</a> 
				</div>
			</div>
		</div>
	</body>
</html>
