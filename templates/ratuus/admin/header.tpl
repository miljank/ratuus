<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Ratuus &bull; {$page|capitalize}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="shortcut icon" href="templates/ratuus/img/favicon3.ico" />
		<link rel="stylesheet" href="templates/ratuus/style/r.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="templates/ratuus/style/confirm.css" type="text/css" media="screen" />
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery.accordion.js"></script> 
		<script type="text/javascript" src="js/jquery.jeditable.js" charset="utf-8"></script> 
		<script type="text/javascript" src="js/jquery.simplevalidate.js"></script>
		<script type="text/javascript" src="js/hint.js"></script> 
		<script type="text/javascript" src="js/accordion.js"></script> 
		<script type="text/javascript" src="js/popup.js"></script> 
		<script type="text/javascript" src="js/jqModal.js"></script>
		<!--[if lte IE 6]>
			<link rel="stylesheet" type="text/css" href="templates/ratuus/style/rie.css" />
		<![endif]-->
	</head>
	<body id="{$page}">
	        <div class="jqmConfirm" id="confirm">
        	        <div id="ex3b" class="jqmConfirmWindow">
	                        <div class="jqmConfirmTitle clearfix">
                                	<h1>Please confirm...</h1>
                        	        <a href="#" class="jqmClose">
                	                        <em>Close</em>
        	                        </a>
	                        </div>

                        	<div class="jqmConfirmContent">
                	                <p class="jqmConfirmMsg"></p>
        	                        <p>Are you sure?</p>
	                        </div>

                        	<input type="submit" value="no" />
                	        <input type="submit" value="yes" />
        	                </p>
	                </div>

	        </div>
		<div id="container">

{$message.sm_message.confirmation}
			<div id="logo">
				<a href="{$link.main}"><img src="templates/ratuus/img/logo.gif"></a>
			</div>
			<div id="menu">
				<a href="{$link.list_domains}" class="m1"></a>
				<a href="{$link.list_admins}" class="m3"></a>
				<a href="{$link.list_config}" class="m2"></a> 
				<a href="{$link.list_logs}" class="m4"></a>
			</div>
			<div id="smallmenu">
				<a href="{$link.logout}" class="logout"></a>
				<a href="#" id="search-toggle" class="search"></a>
			</div>
			<div id="searchbox">
				<form id="myForm" action="" method="get" name="myForm">
					<input name="keyword" id="keyword" class="rounded" type="text" /> 
					<input type="submit" class="button" value="Search" /><br />
					<input type="radio" id="search" name="search" value="users" checked="checked">
					<label for="search">users</label> 
					<input type="radio" name="search" id="search" value="aliases">
					<label for="search">aliases</label> 
					<input type="radio" name="search" id="search" value="domains">
					<label for="search">domains</label>
				</form>
			</div>
			<div id="main">
