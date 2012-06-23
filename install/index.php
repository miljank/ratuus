<?
require_once("tables.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Ratuus &bull; Installation &bull; Welcome</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="shortcut icon" href="../templates/ratuus/img/favicon3.ico" />
		<link rel="stylesheet" href="../templates/ratuus/style/r.css" type="text/css" media="screen" />
		<script type="text/javascript" src="../js/hint.js"></script>
	</head>
<?
$err_msg = "";

function db_connect() {
	global $err_msg;
	if(empty($_POST['db_user']) || empty($_POST['db_pass']) || empty($_POST['db_name']) || empty($_POST['db_host'])) {
		$err_msg = "Please fill all fields!";
		return (FALSE);
	}

	if(!@mysql_connect($_POST['db_host'], $_POST['db_user'], $_POST['db_pass'])) {
		$err_msg =  "Please insert correct data!";
		return (FALSE);
	}

	if(!@mysql_select_db($_POST['db_name'])) {
		$err_msg =  "Please insert correct data!";
		return (FALSE);
	}

	return(TRUE);
}

function create_tables() {
	global $table, $create, $err_msg;
	$query = "SHOW TABLES FROM ". $_POST['db_name'];
	$result = @mysql_query($query);

	if(!$result) {
		$err_msg = "DB Error, could not list tables.";
		return (FALSE);
	}

	while ($row = mysql_fetch_row($result))
		$tables[] = $row[0];

	foreach($table as $tbl_name => $tbl_struct) {
		if(@in_array($tbl_name, $tables)) {
			echo "<br />Table ". $tbl_name ." exists... <span class=\"skip\">SKIP</span>";
		} else {
			echo "<br />Creating table ". $tbl_name ."... ";
			if(@mysql_query($tbl_struct)) {
				echo "<span class=\"ok\">OK</span>";
				if($create[$tbl_name]) {
					echo "<br />Populating table ". $tbl_name ." with data... ";
					if(@mysql_query($create[$tbl_name])) {
						echo "<span class=\"ok\">OK</span>";
					} else {
						echo "<span class=\"error\">ERROR</span>";
						$err_msg = "Could not fill table <b>". $tbl_name ."</b> with data!"
						         . " Check your database configuration.<br />";
						return (FALSE);
					}
				}
			} else {
				echo "<span class=\"error\">ERROR</span>";
				$err_msg = "Could not create table: <b>". $tbl_name ."</b>!"
				         . " Check your database configuration.<br />";
				return (FALSE);
			}
		}
	}

	return (TRUE);
}

function create_admin() {
	global $err_msg;

	$query = @mysql_query("SELECT COUNT(*) FROM admin");
	if(@mysql_num_rows($query) > 0) {
		
	}

	if(empty($_POST['admin']) || empty($_POST['password']) || empty($_POST['password'])) {
		$err_msg = "Not all parameters are set";
		return (FALSE);
	}

	define("CRYPT_MD5", 1);
	$salt = "\$1\$". substr(md5(uniqid(rand(), true)), 0, 8) ."\$";
	$password = crypt(stripslashes($_POST['password']), $salt);

	$query = "INSERT INTO admin (username, password, created, modified, active)"
	       . " VALUES ('". $_POST['admin'] ."', '". $password ."', NOW(), NOW(), 1)";

	if(!@mysql_query($query)) {
		$err_msg = "Could not add admin. Maybe this admin already exists?";
		return (FALSE);
	}

	return (TRUE);
}

function write_config() {
	global $err_msg;
	$file = dirname(dirname(__FILE__)) ."/include/db_config.php";

	$config = "<?\n";
	$config .= "\$db_user = '". $_POST['db_user'] ."';\n";
	$config .= "\$db_pass = '". $_POST['db_pass'] ."';\n";
	$config .= "\$db_name = '". $_POST['db_name'] ."';\n";
	$config .= "\$db_host = '". $_POST['db_host'] ."';\n";
	$config .= "?>";
	
	if(!is_writable($file) || !fwrite(fopen($file, "w"), $config)) {
?>
	<span class="error">ERROR</span></p>
	<div class="note">
		<p>Configuration file is not writable.<br />
		Please copy data below into file: <b><?= $file ?></b></p>
		<p><?= nl2br(htmlspecialchars($config)) ?></p>
		<p>Click continue when done.</p>
	</div>
<?
		return (FALSE);
	}

	echo "<span class=\"ok\">OK</span></p>";
	return (TRUE);
}

if(empty($_POST['step'])) {
?>
	<body id="welcome">
		<div id="welcome">
			<div class="text">
				<h3>Welcome to Ratuus installation</h3>
				<p>Installation contains 3 easy steps. Before proceeding make sure you read <a href="http://www.ratuus.org/documentation/installation/" target="_blank">installation instructions.</a></p>
				<p>Press install to continue.</p><br /><br />

				<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
					<input type="hidden" name="step" value="1" />
					<input type="image" src="../templates/ratuus/img/installbt.png" value="submit" alt="Install" />
				</form>
<?
}

if($_POST['step'] == 1) {
?>
	<body id="install">
		<div id="container">
			<div id="logo">
				<a href="#">
					<img src="../templates/ratuus/img/logo.gif">
				</a>
			</div>
			<div id="menu">
				<div id="installheader">Installation</div>
			</div>
			<div id="main">
				<div id="content">
					<div class="cbgt"></div>
					<div class="cbgm">
						Please enter your database information below.<br /><br />
						<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" id="myForm" name="myForm">
							<dl>
								<dt>
									<label for="db_host">DB Host:</label>
								</dt>
								<dd>
									<input name="db_host" id="db_host" class="rounded" type="text" />
									<span class="hint">Name of your database host.<span class="hint-pointer">&nbsp;</span></span>
								</dd>
								<dt>
									<label for="db_user">DB User:</label>
								</dt>
								<dd>
									<input name="db_user" id="db_user" class="rounded" type="text" />
									<span class="hint">Username you use to access your database.<span class="hint-pointer">&nbsp;</span></span>
								</dd>
								<dt>
									<label for="db_pass">DB Password:</label>
								</dt>
								<dd>
									<input name="db_pass" id="db_pass" class="rounded" type="text" />
									<span class="hint">Password you use to access your database.<span class="hint-pointer">&nbsp;</span></span>
								</dd>
								<dt>
									<label for="db_name">DB Name:</label>
								</dt>
								<dd>
									<input name="db_name" id="db_name" class="rounded" type="text" />
									<span class="hint">Name of your database.<span class="hint-pointer">&nbsp;</span></span>
								</dd>

								<dt class="button">&nbsp;</dt>
								<dd class="button">
									<input type="hidden" name="step" value="2" />
									<input type="submit" class="button" value="Submit" />
								</dd>
							</dl>
						</form>
					</div>
					<div class="cbgb"></div>
				</div>
				<div id="leftbar">
					<a href="#" class="active">DB Information</a>
				</div>

<?
}


if($_POST['step'] == 2) {
?>
	<body id="install">
		<div id="container">
			<div id="logo">
				<a href="#"><img src="../templates/ratuus/img/logo.gif"></a>
			</div>
			<div id="menu">
				<div id="installheader">Installation</div>
			</div>

			<div id="main">
				<div id="content">
					<div class="cbgt"></div>
					<div class="cbgm">
						<div style="padding: 0;margin: 0;overflow:auto">
							<p>Checking DB data... 
<?
	if(!db_connect()) {
		echo "<span class=\"error\">ERROR</span></p>";
		if($err_msg)
			echo "<p>". $err_msg ."</p>";
	} else {
		echo "<span class=\"ok\">OK</span></p>";

		if(!create_tables()) {
			if($err_msg)
				echo "<p>". $err_msg ."</p>";
		} else {
			echo "<p>Writing configuration... ";
			write_config();
?>
							<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
								<input type="hidden" name="db_user" value="<?= $_POST['db_user'] ?>" />
								<input type="hidden" name="db_pass" value="<?= $_POST['db_pass'] ?>" />
								<input type="hidden" name="db_name" value="<?= $_POST['db_name'] ?>" />
								<input type="hidden" name="db_host" value="<?= $_POST['db_host'] ?>" />
								<input type="hidden" name="step" value="3" />
								<input type="submit" name="submit" value="Continue">
							</form>
<?
		}
	}
?>
						</div>
					</div>
					<div class="cbgb"></div>
				</div>
				<div id="leftbar">
					<a href="#" class="active">Create DB</a>
				</div>
<?
}

if($_POST['step'] == 3) {
	if(!db_connect()) {
		echo "Could not connect to DB.";
		if($err_msg)
			echo "<p>". $err_msg ."</p>";
	} else {
		$query = @mysql_query("SELECT COUNT(*) AS admins FROM admin");
		while($res = @mysql_fetch_assoc($query)) {
		        if($res['admins'] > 0)
				$admin = 1;
		}
?>
	<body id="install">
		<style type="text/css">
			div#admin {display: none}
		</style>

		<script type="text/javascript" language="Javascript">
				function showForm() {
					if(document.getElementById("admin").style.display == "none") {
						document.getElementById("admin").style.display = "block";
						document.getElementById("continue").style.display = "none";
					} else {
						document.getElementById("admin").style.display = "none";
						document.getElementById("continue").style.display = "block";
					}
				}
		</script>
		<div id="container">
			<div id="logo">
				<a href="#"><img src="../templates/ratuus/img/logo.gif"></a>
			</div>
			<div id="menu">
				<div id="installheader">Installation</div>
			</div>

			<div id="main">
			<div id="content">
				<div class="cbgt"></div>
				<div class="cbgm">
<?
		if($admin) {
?>
					<p>It seems that you already have admin account set up.<br />
					Click <a href="#" onClick="showForm('admin');">here</a> if you want to set up a new account.</p>
					<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" id="continue">
						<input type="hidden" name="step" value="4" />
						<input type="submit" name="submit" value="Continue">
					</form>
					<div id="admin" style="display: none;">
						<form id="myForm" action="<?= $_SERVER['PHP_SELF'] ?>" method="post" name="myForm">
							<dl>
								<dt>
									<label for="admin">Username:</label>
								</dt>
								<dd>
									<input name="admin" id="admin" class="rounded" type="text" />
								</dd>
								<dt>
									<label for="password">Password:</label>
								</dt>
								<dd>
									<input name="password" id="password" class="rounded" type="password" />
								</dd>
								<dt class="button">&nbsp;</dt>
								<dd class="button">
									<input type="hidden" name="db_user" value="<?= $_POST['db_user'] ?>" />
									<input type="hidden" name="db_pass" value="<?= $_POST['db_pass'] ?>" />
									<input type="hidden" name="db_name" value="<?= $_POST['db_name'] ?>" />
									<input type="hidden" name="db_host" value="<?= $_POST['db_host'] ?>" />
									<input type="hidden" name="step" value="4" />
									<input type="submit" name="submit" value="Continue">
								</dd>
							</dl>
						</form>
					</div>
				</div>
<?
		} else {
?>
					<p>Now please create your admin account.</p>
                                                <form id="myForm" action="<?= $_SERVER['PHP_SELF'] ?>" method="post" name="myForm">
                                                        <dl>
                                                                <dt>
                                                                        <label for="admin">Username:</label>
                                                                </dt>
                                                                <dd>
                                                                        <input name="admin" id="admin" class="rounded" type="text" />
                                                                </dd>
                                                                <dt>
                                                                        <label for="password">Password:</label>
                                                                </dt>
                                                                <dd>
                                                                        <input name="password" id="password" class="rounded" type="password" />
                                                                </dd>
                                                                <dt class="button">&nbsp;</dt>
                                                                <dd class="button">
                                                                        <input type="hidden" name="db_user" value="<?= $_POST['db_user'] ?>" />
                                                                        <input type="hidden" name="db_pass" value="<?= $_POST['db_pass'] ?>" />
                                                                        <input type="hidden" name="db_name" value="<?= $_POST['db_name'] ?>" />
                                                                        <input type="hidden" name="db_host" value="<?= $_POST['db_host'] ?>" />
                                                                        <input type="hidden" name="step" value="4" />
                                                                        <input type="submit" name="submit" value="Continue">
                                                                </dd>
                                                        </dl>
                                                </form>
                                        </div>
<?
		}
	}
?>
			<div class="cbgb"></div>
		</div>
		<div id="leftbar">
			<a href="#" class="active">Create admin user</a>
		</div>
<?
}

if($_POST['step'] == 4) {
?>
	<body id="install">
		<div id="container">
			<div id="logo">
				<a href="#"><img src="../templates/ratuus/img/logo.gif"></a>
			</div>
			<div id="menu">
				<div id="installheader">Installation</div>
			</div>
			<div id="main">
				<div id="content">
					<div class="cbgt">
				</div>
				<div class="cbgm">
					<div style="padding: 0;margin: 0;overflow:auto">
<?
	if($_POST['admin']) {
		echo "<p>Creating admin... ";
		if(!db_connect() || !create_admin()) {
			echo "<span class=\"error\">ERROR</span></p>";
			if($err_msg)
				echo "<p>". $err_msg ."</p>";
		} else {
			echo "<span class=\"ok\">OK</span></p>";
			echo "Congratulation!!! <b>RATUUS</b> was successfully installed!<br /><br />";
			echo "You can now <b><a href=\"../\">log in</a></b> using: <br /><br />";
			echo "Username: ". $_POST['admin'] ."<br />";
			echo "Password: ". $_POST['password'] ."<br />";
		}
	} else {
		echo "<p>Congratulation!!! <b>RATUUS</b> was successfully installed!</p>";
		echo "<p><b><a href=\"../\">Log in</a></b> now!</p>";
	}
?>
					</div>
				</div>
				<div class="cbgb"></div>
			</div>
			<div id="leftbar">
				<a href="#" class="active">Finish</a>
			</div>
<?
}

?>
			</div>
		</div>
	</body>
</html>
