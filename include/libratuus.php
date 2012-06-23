<?php
class Ratuus {
	var $db_host = "";
	var $db_user = "";
	var $db_pass = "";
	var $db_name = "";
	var $username = "";
	var $domain = "";
	var $password = "";
	var $password_2 = "";
	var $name = "";
	var $conn = 0;
	var $num_rows = 0;
	var $db_errno = 0;
	var $db_errstr = "";
	var $domain_list = array();
	var $domain_no = 0;
	var $user_list = array();
	var $user_no = 0;
	var $add = "";
	var $delete = "";
	var $list = "";
	var $config = array();
	var $description = "";
	var $aliases = 0;
	var $alias_list = array();
	var $alias_no = 0;
	var $alias = "";
	var $destination = "";
	var $mailboxes = 0;
	var $maxquota = 0;
	var $transport = "";
	var $backupmx = 0;
	var $active = "";
	var $remember = "";
	var $domain_admin = "";
	var $domain_admins = "";
	var $login = "";
	var $priv_level = "";
	var $page = 1;
	var $keyword = "";
	var $search = "";
	var $search_no = "";

	#
	# Define levels of privileges
	# 3 = admin (superuser); 
	# 2 = domain_admin (user with admin privileges limited to certain domains);
	# 1 = user (regular non-privileged user);
	var $priv_admin = 3;
	var $priv_domain_admin = 2;
	var $priv_user = 1;

#==============================================================#
#                                                              #
#                     DB Functions                             #
#                                                              #
#==============================================================#

	#
	# function creates a connection to a DB
	# and returns a connection ID if success
	# returns FALSE in case of an error
	function db_connect($db_host, $db_user, $db_pass, $db_name) {
		# reset error values
		$this->db_errno = 0;
		$this->db_errstr = "";

		# connect if not connected
		if($this->conn == 0) {
			$this->conn = @mysql_connect($db_host, $db_user, $db_pass);
			# report error in case connection was not successful
			if(!$this->conn) {
				$this->db_errno = mysql_errno();
				$this->db_errstr = mysql_error();
				$this->error("Could not connect to DB server.");
				return (FALSE);
			}

			# report error in case we are not able to select a DB
			if(!@mysql_select_db($db_name)) {
				$this->db_errno = mysql_errno();
				$this->db_errstr = mysql_error();
				$this->error("Could not select database: ". $db_name);
				return (FALSE);
			}
		}
		# connection is successfull
		return ($this->conn);
	}

	#
	# function closes a working DB connection
	# always returns TRUE
	function db_disconnect() {
		# close connection if opened
		if($this->conn != 0) {
			@mysql_close($this->conn);
			$this->conn = 0;
		}
		return (TRUE);
	}

	#
	# function issues a query to the db
	# returns query ID if success
	function ask_db($query) {
		$this->num_rows = 0;
		$this->query_id = @mysql_query($query, $this->conn);
		$this->db_errno = mysql_errno();
		$this->db_errstr = mysql_error();

		if($this->db_errno) {
			$this->error("Problem with query: ". $query);
			return (FALSE);
		}

		$this->num_rows = @mysql_affected_rows($this->conn);
		return ($this->query_id);
	}

	#
	# function processs the result of a db query
	# returns result of a query in an array
	# FALSE when we get to the end of array
	function fetch_result() {
		$this->row = @mysql_fetch_array($this->query_id, MYSQL_ASSOC);
		$this->db_errno = mysql_errno();
		$this->db_errstr = mysql_error();

		if($this->db_errno) {
			$this->error("Problem with 'fetch_array' function.");
			return (FALSE);
		}

		if(is_array($this->row))
			return ($this->row);
		$this->free_result();
		return (FALSE);
	}

	#
	# function frees mysql query result
	function free_result() {
		if($this->query_id)
			@mysql_free_result($this->query_id);
		$this->result_id = 0;
		return (TRUE);
	}

#==============================================================#
#                                                              #
#                  Message handling                            #
#                                                              #
#==============================================================#

	#
	# function handles errors
	function error($error_str) {
		global $ssmarty;

		/*
		if($this->db_errno || $this->db_errstr) {
			$this->free_result();
			$error_str .= "\nError: ". $this->db_errstr ."(". $this->db_errno .")";
		}
		*/

		$ssmarty->message(nl2br(htmlspecialchars($error_str)), 'error');
	}

	#
	# function handles information messages
	function message($msg) {
		global $ssmarty;

		$ssmarty->message(nl2br(htmlspecialchars($msg)), 'confirmation');
	}

	function notice($msg) {
		global $ssmarty;

		$ssmarty->message(nl2br(htmlspecialchars($msg)), 'notice');
	}

	function text_holder($msg) {
		global $ssmarty;

		$ssmarty->text_holder(nl2br(htmlspecialchars($msg)), 'text_holder');
	}

#==============================================================#
#                                                              #
#               Variable checking and assignement              #
#                                                              #
#==============================================================#

	#
	# function escapes MySQL input
	function escape($input) {
		if(get_magic_quotes_gpc())
			$input = stripslashes($input);
		$input = mysql_real_escape_string($input);
		return (addcslashes($input, '%_'));
	} 

	#
	# function gets the input and assigns it to correct variables
	function check_input() {
		# POST requests
		if(eregi('^[_a-z0-9.@-]+$', $_POST['username'])) 
			$this->username = $_POST['username'];
		if(eregi('^[_a-z0-9. ,@-]+$', $_POST['domain_admins']))
			$this->domain_admins = $_POST['domain_admins'];
		if(eregi('^[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$', $_POST['domain'])) 
			$this->domain = $_POST['domain'];
		if(!empty($_POST['password'])) 
			$this->password = $this->escape($_POST['password']);
		if(!empty($_POST['password_2']))
			$this->password_2 =  $this->escape($_POST['password_2']);
		if(!empty($_POST['name'])) 
			$this->name = $this->escape($_POST['name']);
		if(!empty($_POST['quota'])) 
			$this->quota = $this->escape($_POST['quota']);
		if(!empty($_POST['active'])) 
			$this->active = $this->escape($_POST['active']);
		if(!empty($_POST['add']))
			$this->add = $this->escape($_POST['add']);
		if(!empty($_POST['config_opt']))
			$this->config_opt = $this->escape($_POST['config_opt']);
		if(!empty($_POST['config_value']))
			$this->config_value = $this->escape($_POST['config_value']);
		if(!empty($_POST['change']))
			$this->change = $this->escape($_POST['change']);
		if(!empty($_POST['description']))
			$this->description = $this->escape($_POST['description']);
		if(!empty($_POST['aliases']))
			$this->aliases = $this->escape($_POST['aliases']);
		if(!empty($_POST['mailboxes']))
			$this->mailboxes = $this->escape($_POST['mailboxes']);
		if(!empty($_POST['maxquota']))
			$this->maxquota = $this->escape($_POST['maxquota']);
		if(!empty($_POST['quota_value']))
			$this->quota_value = $this->escape($_POST['quota_value']);
		if(!empty($_POST['transport']))
			$this->transport = $this->escape($_POST['transport']);
		if(!empty($_POST['backupmx']))
			$this->backupmx = $this->escape($_POST['backupmx']);
		if(eregi('^[_a-z0-9.@-]+$', $_POST['alias']))
			$this->alias = $_POST['alias'];
		if(!empty($_POST['destination']))
			$this->destination = $this->escape($_POST['destination']);
		if(!empty($_POST['login']))
			$this->login = $this->escape($_POST['login']);
		if(!empty($_POST['remember']))
			$this->remember = $this->escape($_POST['remember']);
		if(eregi('^[_a-z0-9.@-]+$', $_POST['keyword']))
			$this->keyword = $_POST['keyword'];
		if(!empty($_POST['search']))
			$this->search = $this->escape($_POST['search']);

		# GET requests
		if(ereg('^[_a-z0-9.@-]+$', $_GET['username'])) 
			$this->username = $_GET['username'];
		if(eregi('^[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$', $_GET['domain'])) 
			$this->domain = $this->escape($_GET['domain']);
		if(!empty($_GET['add']))
			$this->add = $this->escape($_GET['add']);
		if(!empty($_GET['delete']))
			$this->delete = $this->escape($_GET['delete']);
		if(!empty($_GET['list']))
			$this->list = $this->escape($_GET['list']);
		if(!empty($_GET['config_opt']))
			$this->config_opt = $this->escape($_GET['config_opt']);
		if(!empty($_GET['change']))
			$this->change = $this->escape($_GET['change']);
		if(ereg('^[_a-z0-9.@-]+$', $_GET['alias']))
			$this->alias = $_GET['alias'];
		if(!empty($_GET['sidebar']))
			$this->sidebar = $this->escape($_GET['sidebar']);
		if(!empty($_GET['form']))
			$this->form = $this->escape($_GET['form']);
		if(!empty($_GET['action']))
			$this->action = $this->escape($_GET['action']);
		if(!empty($_GET['page']))
			$this->page = $this->escape($_GET['page']);
		if(eregi('^[_a-z0-9.@-]+$', $_GET['keyword']))
			$this->keyword = $_GET['keyword'];
		if(!empty($_GET['search']))
			$this->search = $this->escape($_GET['search']);

		# SESSION parameters
		if(!empty($_SESSION['username']))
			$this->auth_user = $this->escape($_SESSION['username']);
	}

#==============================================================#
#                                                              #
#                   Configuration function                     #
#                                                              #
#==============================================================#

	#
	# function adds configuration into DB
	function add_config($option, $value) {
		$query = "INSERT INTO config (config_opt, config_value)"
		       . " VALUES ('$config_opt', '$config_value')";
		$this->ask_db($query);

		if($this->get_config($config_opt))
			return (TRUE);

		return (FALSE);
	}

	#
	# function deletes configuration options
	function delete_config($option) {
		$query = "DELETE FROM config"
		       . " WHERE config_opt = '$config_opt'";
		$this->ask_db($query);

		if(!$this->get_config($config_opt))
			return (TRUE);

		return (FALSE);
	}

	#
	# function reads configuration from DB
	function get_config($config = NULL) {
		$query = "SELECT * FROM config";
		if($config)
			$query .= " WHERE config_opt = '$config'";
		$this->ask_db($query);

		if($this->num_rows > 0) {
			while($row = $this->fetch_result())
				$this->config[stripslashes($row['config_opt'])] = stripslashes($row['config_value']);
			return ($this->config);
		}
		return (FALSE);
	}

	#
	# function modifies configuration
	function change_config($option, $value) {
		if($option == 'user\_quota' || $option == 'domain\_quota') {
			$value = explode(" ", $value);
			if(ereg('[kK][bB]', $value[1]))
				$n = 1;
			if(ereg('[mM][bB]', $value[1]))
				$n = 1000;
			if(ereg('[gG][bB]', $value[1]))
				$n = 1000000;
			$value = $value[0] * $n;
		}

		$query = "UPDATE config SET config_value = '$value'"
		       . " WHERE config_opt = '$option'";
		$this->ask_db($query);

		$this->get_config($option);
		if($this->config[stripslashes($option)] == stripslashes($value))
			return (TRUE);

		return (FALSE);
	}

#==============================================================#
#                                                              #
#                        ACL functions                         #
#                                                              #
#==============================================================#

	#
	#
	function is_allowed($func_name) {
		$this->function_privileges = array(
			'add_config' => $this->priv_admin,
			'get_config' => $this->priv_admin,
			'delete_config' => $this->priv_admin,
			'change_config' => $this->priv_admin,
			'add_admin' => $this->priv_admin,
			'list_admins' => $this->priv_admin,
			'change_admin' => $this->priv_admin,
			'delete_admin' => $this->priv_admin,
			'add_domain' => $this->priv_admin,
			'delete_domain' => $this->priv_admin,
			'change_domain' => $this->priv_admin,
			'purge_log' => $this->priv_domain_admin,
			'list_domain_admins' => $this->priv_domain_admin,
			'add_domain_admin' => $this->priv_domain_admin,
			'change_domain_admin' => $this->priv_domain_admin,
			'delete_domain_admin' => $this->priv_domain_admin,
			'list_domains' => $this->priv_domain_admin,
			'add_user' => $this->priv_domain_admin,
			'delete_user' => $this->priv_domain_admin,
			'delete_domain_users' => $this->priv_domain_admin,
			'change_user' => $this->priv_domain_admin,
			'add_alias' => $this->priv_domain_admin,
			'change_alias' => $this->priv_domain_admin,
			'list_alias' => $this->priv_domain_admin,
			'delete_alias' => $this->priv_domain_admin,
			'search' => $this->priv_domain_admin,
			'read_log' => $this->priv_user,
			'list_domain_users' => $this->priv_user,
			'change_password' => $this->priv_user
		);

		if($this->function_privileges[$func_name] &&
		   $this->priv_level >= $this->function_privileges[$func_name])
				return (TRUE);

		return (FALSE);

	}

	#
	# function checks if username is part of domain administration group
	function get_priv_level() {
		if(!$this->auth_user)
			$this->auth_user = $_SESSION['username'];

		if($this->is_admin($this->auth_user))
			return ($this->priv_admin);

		if($this->is_domain_admin($this->auth_user))
			return ($this->priv_domain_admin);

		if($this->check_user($this->auth_user))
			return ($this->priv_user);

		return (FALSE);
	}

#==============================================================#
#                                                              #
#                 AUTHENTICATION functions                     #
#                                                              #
#==============================================================#

	#
	# function checks if users password matches with
	# password we have in database
	function verify_user($username, $password, $clear = NULL) {
		$username = $this->escape($username);
		$query = "SELECT password FROM mailbox WHERE username = '$username' AND active = 1";

		if($this->is_admin($username))
			$query = "SELECT password FROM admin WHERE username = '$username' AND active = 1";

		$this->ask_db($query);

		if($this->num_rows == 1) {
			while($row = $this->fetch_result())
				$user_password = $row['password'];

			if($clear) {
				$salt = explode('$', $user_password);
				$password = $this->make_password($password, "\$1\$". $salt[2] ."\$");
			}
			if($user_password == $password)
				return ($user_password);
		}
		return (FALSE);
	}

	#
	# function verifies if login dana is valid
	function check_login() {
		if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])) {
			$_SESSION['username'] = $_COOKIE['cookname'];
			$_SESSION['password'] = $_COOKIE['cookpass'];
		}
	
		if(isset($_SESSION['username']) && isset($_SESSION['password'])) {
			if($this->verify_user($_SESSION['username'], $_SESSION['password'])) {
				$this->priv_level = $this->get_priv_level();
				return (TRUE);
			} else {
				unset($_SESSION['username']);
				unset($_SESSION['password']);
				return (FALSE);
			}
		} 
		return (FALSE);
	}

	#
	# function logins user 
	function do_login() {
		if($this->username && $this->password) {
			if($password = $this->verify_user($this->username, stripslashes($this->password), 'clear')) {
				$_SESSION['username'] = $this->username;
				$_SESSION['password'] = $password;
				$this->priv_level = $this->get_priv_level();

				if($this->remember) {
					setcookie("cookname", $_SESSION['username'], time()+60*60*24*100, "/");
					setcookie("cookid", $_SESSION['username'], time()+60*60*24*100, "/");
					setcookie("cookpass", $_SESSION['password'], time()+60*60*24*100, "/");
				}
				print $_SESSION[''];
				return(TRUE);
			}
		}
		return (FALSE);
	}			

	#
	# function does a logout
	function do_logout() {
		if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){
			setcookie("cookname", "", time()-60*60*24*100, "/");
			setcookie("cookpass", "", time()-60*60*24*100, "/");
		}

		unset($_SESSION['username']);
		unset($_SESSION['password']);
		$_SESSION = array();
		session_destroy();
	}

#==============================================================#
#                                                              #
#                     ADMIN functions                          #
#                                                              #
#==============================================================#

	#
	# function checks if username is part of the main administrator group
	function is_admin($username = NULL) {
		if(!$username)
			$username = $this->auth_user;

		$query = "SELECT * FROM admin WHERE username = '$username'";
		$this->ask_db($query);

		if($this->num_rows == 1)
			return (TRUE);

		return (FALSE);
	}

	#
	# function adds a new admin
	function add_admin($username, $password, $active) {
		$password = $this->make_password(stripslashes($password));
		if($this->active == 'no') {
			$this->active = 0;
		} else {
			$this->active = 1;
		}

		$query = "INSERT INTO admin (username, password, active, created, modified)"
		       . " VALUES ('$username', '$password', $this->active, NOW(), NOW())";
		$this->ask_db($query);

		if($this->is_admin($username))
			return (TRUE);

		return (FALSE);
	}

	#
	# function lists all admins
	function list_admins($admin = NULL) {
		$query = "SELECT * FROM admin";

		# generate the pagination
		$pages = $this->make_pages($query, 'list=admins');

		# list specific admin ?
		if($admin)
			$query .= " WHERE username = '$admin'";

		# add page limits
		$offset = $this->page * $this->config['limit'] - $this->config['limit'];
		$query .= " LIMIT $offset, ". $this->config['limit'];

		$this->ask_db($query);

		if($this->num_rows > 0) {
			$this->admin_no = $this->num_rows;
			$this->admin_list = "";
			$this->admin_list[0]['pages'] = $pages['links'];

			$i = 0;
			while($row = $this->fetch_result()) {
				$this->admin_list[$i]['username'] = $row['username'];
				$this->admin_list[$i]['active'] = $row['active'];
				$this->admin_list[$i]['created'] = $row['created'];
				$this->admin_list[$i]['modified'] = $row['modified'];

				$i++;
			}
			$this->admin_list[0]['no'] = $pages['row_no'];
			return ($this->admin_list);
		}
		return (FALSE);
	}

	#
	# function changes admin
	function change_admin($username, $active, $password = NULL) {
		if($this->active == 'no') {
			$this->active = 0;
		} else {
			$this->active = 1;
		}

		if($password)
			$this->change_password($username, $password);

		$query = "UPDATE admin SET active = $this->active, modified = NOW()"
		       . " WHERE username = '$username'";
		$this->ask_db($query);

		return (TRUE);
	}

	#
	# function deletes admin
	function delete_admin($username) {
		$query = "DELETE FROM admin WHERE username = '$username'";
		$this->ask_db($query);

		if(!$this->is_admin($username))
			return (TRUE);

		return (FALSE);
	}


#==============================================================#
#                                                              #
#                  DOMAIN ADMIN functions                      #
#                                                              #
#==============================================================#

	#
	#
	function is_domain_admin($username = NULL, $domain = NULL) {
		if(!$username)
			$username = $this->auth_user;

		$query = "SELECT domain FROM domain_admins"
		       . " WHERE username = '$username'";

		if($domain)
			$query .= " AND domain = '$domain'";

		$this->ask_db($query);

		if($this->num_rows > 0)
			return (TRUE);

		return (FALSE);
	}

	#
	#
	function list_domain_admins($domain) {
		$query = "SELECT * FROM domain_admins WHERE domain = '$domain'";

		$this->ask_db($query);

		if($this->num_rows > 0) {
			$this->domain_admin = "";

			$i = 0;
			while($row = $this->fetch_result()) {
				$this->domain_admin = $this->domain_admin ? $this->domain_admin .", ". $row['username'] : $row['username'];

				$i++;
			}
			return ($this->domain_admin);
		}
		return (FALSE);
	
	}

	#
	# function adds new domain admin
	function add_domain_admin($username, $domain, $active) {
		if($this->active == 'no') {
			$this->active = 0;
		} else {
			$this->active = 1;
		}

		$query = "INSERT INTO domain_admins (username, domain, active, created)"
		       . " VALUES ('$username', '$domain', $active, NOW())";
		$this->ask_db($query);

		if($this->is_domain_admin($username, $domain))
			return (TRUE);

		return (FALSE);
	}

	#
	# function deletes a domain admin
	function delete_domain_admin ($domain, $username = NULL) {
		$query = "DELETE FROM domain_admins WHERE domain = '$domain'";
		if($username)
			$query = " AND WHERE username = '$username'";

		$this->ask_db($query);

		if(!$this->is_domain_admin($username, $domain))
			return (TRUE);

		return (FALSE);
	}

	#
	# function changes domain admin
	function change_domain_admin($username, $active, $domain = NULL) {
		if($this->active == 'no') {
			$this->active = 0;
		} else {
			$this->active = 1;
		}

		$query = "UPDATE domain_admins SET active = $this->active"
		       . " WHERE username = '$username'";
		if($domain)
			$query = " AND domain = '$domain'";

		$this->ask_db($query);

		if($this->num_rows > 0)
			return (TRUE);

		return (FALSE);
	}

#==============================================================#
#                                                              #
#                     DOMAIN functions                         #
#                                                              #
#==============================================================#

	#
	# function check if the domain already exists
	# returns TRUE if domain exists
	function check_domain($domain) {
		$query = "SELECT * FROM domain WHERE domain = '". $domain ."'";
		$this->ask_db($query);

		if($this->num_rows > 0) {
			$this->free_result();
			return (TRUE);
		}
		return (FALSE);
	}

	#
	# function lists all domains
	# returns array with domain names
	# or FALSE if no domains are yet created
	# if $domain argument is passed function
	# will return only data for this domain
	function list_domains($domain = NULL) {

		# first we generate proper query according 
		# to users privilage
		if($this->priv_level == $this->priv_admin) {
			$query = "SELECT * FROM domain";
			if($domain)
				$query .= " WHERE domain = '$domain'";
		}

		if($this->priv_level == $this->priv_domain_admin) {
			$query = "SELECT * FROM domain"
			       . " WHERE domain IN"
			       . " (SELECT domain FROM domain_admins WHERE username = '$this->auth_user')";
			if($domain)
				$query .= " AND domain = '$domain'";
		}

		# then we deal with the pagination - brrrrr
		# generate the pagination
		$pages = $this->make_pages($query, 'list=domains');

		# add page limits
		$offset = $this->page * $this->config['limit'] - $this->config['limit'];
		$query .= " LIMIT $offset, ". $this->config['limit'];

		$this->ask_db($query);

		if($this->num_rows > 0) {
			$this->domain_no = $this->num_rows;
			$this->domain_list = "";
			$this->domain_list[0]['pages'] = $pages['links'];

			$i = 0;
			while($row = $this->fetch_result()) {
				$this->domain_list[$i]['domain'] = $row['domain'];
				$this->domain_list[$i]['description'] = $row['description'];
				$this->domain_list[$i]['aliases'] = $row['aliases'];
				$this->domain_list[$i]['mailboxes'] = $row['mailboxes'];
				$this->domain_list[$i]['maxquota'] = $row['maxquota'];
				$this->domain_list[$i]['nice_quota'] = $this->nice_quota($row['maxquota']);
				$this->domain_list[$i]['transport'] = $row['transport'];
				$this->domain_list[$i]['backupmx'] = $row['backupmx'];
				$this->domain_list[$i]['created'] = $row['created'];
				$this->domain_list[$i]['modified'] = $row['modified'];
				$this->domain_list[$i]['active'] = $row['active'];

				$i++;
			}
			for($i = 0; $i < $this->domain_no; $i++) {
				$this->get_domain_res($this->domain_list[$i]['domain']);
				$this->domain_list[$i]['used_aliases'] = $this->used_res['used_aliases'];
				$this->domain_list[$i]['used_mailboxes'] = $this->used_res['used_mailboxes'];
				$this->domain_list[$i]['used_quota'] = $this->used_res['used_quota'];
				$this->domain_list[$i]['nice_used_quota'] = $this->nice_quota($this->used_res['used_quota']);
				$this->domain_list[$i]['used_quota']['real_quota'] = $this->used_res['used_quota'];
			}
			for($i = 0; $i < $this->domain_no; $i++)
				$this->domain_list[$i]['admin'] = $this->list_domain_admins($this->domain_list[$i]['domain']);
			$this->domain_list[0]['no'] = $pages['row_no'];
			return ($this->domain_list);
		}
		return (FALSE);
	}

	#
	# function adds new domain
	function add_domain($domain, $description, $aliases, $mailboxes, $maxquota, $transport, $backupmx, $active) {
		if(!$this->description)
			$this->description = "";
		if(!$this->aliases)
			$this->aliases = $this->config['aliases'];
		if(!$this->mailboxes)
			$this->mailboxes = $this->config['mailboxes'];
		if(!$this->maxquota)
			$this->maxquota = $this->config['domain_quota'];
		if(!$this->transport)
			$this->transport = $this->config['transport'];
		if(!$this->backupmx)
			$this->backupmx = "";
		if($this->active == 'no') {
			$this->active = 0;
		} else {
			$this->active = 1;
		}

		$query = "INSERT INTO domain (domain, description, aliases, mailboxes,"
		       . " maxquota, transport, backupmx, created, modified, active)"
                       . " VALUES ('$domain', '$this->description', $this->aliases, $this->mailboxes,"
                       . " $this->maxquota, '$this->transport', '$this->backupmx', NOW(), NOW(), $this->active)";
		$this->ask_db($query);

		if($this->check_domain($domain)) {
			if($this->domain_admins) {
				$domain_admins = explode(',', $this->domain_admins);
				for($i = 0; $i < count($domain_admins); $i++)
					$this->add_domain_admin(trim($domain_admins[$i]), $domain, 1);
			}
			return (TRUE);
		}
		return (FALSE);
	}

	#
	# function deletes existing domain
	function delete_domain($domain) {
		if($this->delete_domain_users($domain)) {
			$query[0] = "DELETE FROM domain_admins WHERE domain = '$domain'";
			$query[1] = "DELETE FROM domain WHERE domain = '$domain'";

			for($i = 0; $i < count($query); $i++)
				$this->ask_db($query[$i]);

			if(!$this->check_domain($domain))
				return (TRUE);
		}
		return (FALSE);
	}

	#
	# function changes domain settings
	function change_domain($domain, $description, $aliases, $maxquota, $mailboxes, $transport, $backupmx, $active) {
		if($this->active == 'no') {
			$this->active = 0;
		} else {
			$this->active = 1;
		}
		$query = "UPDATE domain SET description = '$description', aliases = $aliases,"
		       . " maxquota = $maxquota, mailboxes = $mailboxes, transport = '$transport',"
		       . " backupmx = $backupmx, active = $this->active, modified = NOW()"
		       . " WHERE domain = '$domain'";

		if($this->ask_db($query)) {
			$this->delete_domain_admin($domain);
			if($this->domain_admins) {
				$domain_admins = explode(',', $this->domain_admins);
				for($i = 0; $i < count($domain_admins); $i++)
					$this->add_domain_admin(trim($domain_admins[$i]), $domain, 1);
			}
			return (TRUE);
		}

		return (FALSE);
	}

	#
	# function calculates free resources for a domain
	function get_domain_res($domain) {
		$query = "SELECT COUNT(address) AS used_aliases FROM alias"
		       . " WHERE domain = '$domain' AND address <> goto";
		$this->ask_db($query);

		while($row = $this->fetch_result())
			$this->used_res['used_aliases'] = $row['used_aliases'];

		$query = "SELECT COUNT(username) AS used_mailboxes, IFNULL(SUM(quota), 0) AS used_quota"
		       . " FROM mailbox WHERE domain = '$domain'";
		$this->ask_db($query);

		while($row = $this->fetch_result()) {
			$this->used_res['used_mailboxes'] = $row['used_mailboxes'];
			$this->used_res['used_quota'] = $row['used_quota'];
		}
		return ($this->used_res);
	}

#==============================================================#
#                                                              #
#                      USER functions                          #
#                                                              #
#==============================================================#

	#
	# function checks if the user already exists
	# returns TRUE if user exists
	function check_user($email, $domain = NULL) {
		if($domain)
			$email = $email ."@". $domain;

		$query = "SELECT * FROM mailbox WHERE username ='$email'";
		$this->ask_db($query);

		if($this->num_rows > 0) {
			$this->free_result();
			return (TRUE);
		}
		return (FALSE);
	}

	#
	# function lists all users on a particular domain
	# returns a list of user in an array
	# or FALSE if no users are created yet
	function list_domain_users($domain = NULL, $user = NULL) {
		$query = "SELECT * FROM mailbox WHERE domain = '". $domain ."'";
		if($user) {
			if(!$domain) {
				$query = "SELECT * FROM mailbox WHERE username = '$user'";
			} else {
				$query .= " AND username = '$user'";
			}
		}

		if($this->priv_level == $this->priv_domain_admin)
			$query .= " AND domain IN"
			       .  " (SELECT domain FROM domain_admins WHERE username = '$this->auth_user')";

		if($this->priv_level == $this->priv_user)
			$query = "SELECT * FROM mailbox WHERE username = '$this->auth_user'";

		# generate the pagination
		$pages = $this->make_pages($query, 'list=users&domain='. $domain);

		# add page limits
		$offset = $this->page * $this->config['limit'] - $this->config['limit'];
		$query .= " LIMIT $offset, ". $this->config['limit'];

		$this->ask_db($query);

		if($this->num_rows > 0) {
			$this->user_no = $this->num_rows;
			$this->user_list = "";
			$this->user_list[0]['pages'] = $pages['links'];

			$i = 0;
			while($row = $this->fetch_result()) {
				$this->user_list[$i]['username'] = $row['username'];
				$this->user_list[$i]['name'] = $row['name'];
				$this->user_list[$i]['quota'] = $row['quota'];
				$this->user_list[$i]['nice_quota'] = $this->nice_quota($row['quota']);
				$this->user_list[$i]['domain'] = $row['domain'];
				$this->user_list[$i]['created'] = $row['created'];
				$this->user_list[$i]['modified'] = $row['modified'];
				$this->user_list[$i]['active'] = $row['active'];
				
				$i++;
			}
			$this->user_list[0]['no'] = $pages['row_no'];
			return ($this->user_list);
		}
		return (FALSE);
	}

	#
	# function encrypts password
	function make_password($password, $salt = NULL) {
		define("CRYPT_MD5", 1);
		if(!$salt)
			$salt = "\$1\$". substr(md5(uniqid(rand(), true)), 0, 8) ."\$";
		return crypt(stripslashes($password), $salt);
	}

	#
	# function changes password
	function change_password($username, $password) {
		$new_password = $this->make_password(stripslashes($password));
	
		if($this->is_admin($username)) {
			$query = "UPDATE admin SET password = '$new_password', modified = NOW()"
			       . " WHERE username = '$username'";
		} else {
			$query = "UPDATE mailbox SET password = '$new_password', modified = NOW()"
			       . " WHERE username = '$username'";
		}

		$this->ask_db($query);

		if($this->num_rows > 0) {
			#print "Password was successfully changed.";
			return (TRUE);
		}

		return (FALSE);
	}

	#
	# function adds a new user
	# accepts $username, $password, $name, $quota, $domain, $active
	# generates $maildir
	# returns TRUE if success
	function add_user($username, $password, $name = NULL, $quota = NULL, $domain, $active = NULL) {
		$maildir = $domain ."/". $username ."/";
		$email = $username ."@". $domain;
		$password = $this->make_password(stripslashes($password));

		if(!$name)
			$name = "";
		if(!$quota)
			$quota = $this->config['user_quota'];
		if($this->active == 'no') {
			$this->active = 0;
		} else {
			$this->active = 1;
		}

		$allowed_res = $this->list_domains($domain);
		$used_res = $this->get_domain_res($domain);

		if(($used_res['used_quota'] + $quota) > $allowed_res[0]['maxquota'] &&
		   $allowed_res[0]['maxquota'] > 0) {
			$this->error("Domain exceeds allowed quota.\n");
		} else {
			if(($used_res['used_mailboxes'] + 1) > $allowed_res[0]['mailboxes'] &&
			   $allowed_res[0]['mailboxes'] > 0) {
				$this->error("Domain exceeds allowed number of mailboxes.\n");
			} else {
				$query = "INSERT INTO mailbox (username, password, name, maildir, quota,"
				       . " domain, created, modified, active)"
				       . " VALUES ('$email', '$password', '$name', '$maildir', '$quota',"
				       . " '$domain', NOW(), NOW(), $this->active)";
				$this->ask_db($query);

				if($this->check_user($email)) {
					if($this->check_alias($email))
						$this->delete_alias($email);

					$this->add_alias($email, $domain);
					if($this->check_alias($email))
						return (TRUE);
				}
			}
		}
		return (FALSE);
	}

	#
	# function deletes specific user
	function delete_user($username, $domain) {
		$query[0] = "DELETE FROM alias WHERE address = '$username' AND domain = '$domain'";
		$query[1] = "DELETE FROM mailbox WHERE username = '$username' AND domain = '$domain'";
		$query[2] = "DELETE FROM domain_admins WHERE username = '$username'";
		#$query[3] = "DELETE FROM vacation WHERE email = '$username' AND domain = '$domain'";

		for($i = 0; $i < count($query); $i++)
			$this->ask_db($query[$i]);

		if(!$this->check_user($username))
			return (TRUE);

		return (FALSE);
	}

	#
	# function deletes all users on a existing domain
	function delete_domain_users($domain) {
		$query[0] = "DELETE FROM alias WHERE domain = '$domain'";
		$query[1] = "DELETE FROM mailbox WHERE domain = '$domain'";
		#$query[2] = "DELETE FROM vacation WHERE domain = '$domain'";

		for($i = 0; $i < count($query); $i++)
			$this->ask_db($query[$i]);

		if(!$this->list_domain_users($domain))
			return (TRUE);

		return (FALSE);
	}

	#
	# function changes user data
	function change_user($username, $name = NULL, $password = NULL, $quota = NULL, $active = NULL) {
		if($active == 'no') {
			$active = 0;
		} else {
			$active = 1;
		}

		if($this->priv_level == $this->priv_user) {

			if(!empty($password)) {
				if(!$this->change_password($this->auth_user, $password))
					return (FALSE);
			}

			if($name) {
				$query = "UPDATE mailbox SET name = '$name'"
				       . " WHERE username = '$this->auth_user'";

				if(!$this->ask_db($query))
					return (FALSE);
			}
			return(TRUE);
		}

		if($password)
			$this->change_password($username, $password);

		$query = "UPDATE mailbox SET name = '$name', quota = $quota,"
		       . " active = $active, modified = NOW()"
		       . " WHERE username = '$username'";

		$user_res = $this->list_domain_users($domain, $username);
		$allowed_res = $this->list_domains($domain);
		$used_res = $this->get_domain_res($domain);

		if((($used_res['used_quota'] - $user_res[0]['quota']) + $quota) > $allowed_res[0]['maxquota'] &&
		   $allowed_res[0]['maxquota'] > 0) {
			$this->error("Domain exceeds allowed quota.\n");
		} else {
			if($this->ask_db($query))
				return (TRUE);
		}
		return (FALSE);
	}

#==============================================================#
#                                                              #
#                     ALIAS functions                          #
#                                                              #
#==============================================================#

	#
	# function checks if alias exists
	function check_alias($alias) {
		$query = "SELECT * FROM alias WHERE address = '$alias'";
		$this->ask_db($query);

		if($this->num_rows > 0) {
			$this->free_result();
			return (TRUE);
		}
		return (FALSE);
	}

	#
	# function adds new alias
	function add_alias($alias, $domain, $destination = NULL) {
		if(!$destination)
			$destination = $alias;
		if($this->active == 'no') {
			$this->active = 0;
		} else {
			$this->active = 1;
		}


		$allowed_res = $this->list_domains($domain);
		$used_res = $this->get_domain_res($domain);

		if(($used_res['used_aliases'] + 1) > $allowed_res[0]['aliases'] &&
		   $allowed_res[0]['aliases'] > 0) {
			$this->error("Domain exceeds allowed number of aliases.\n");
		} else {
			$query = "INSERT INTO alias (address, goto, domain, created, modified, active)"
			       . " VALUES ('$alias', '$destination', '$domain', NOW(), NOW(), $this->active)";
			$this->ask_db($query);

			if($this->check_alias($alias))
				return (TRUE);
		}
		return (FALSE);
	}

	#
	# function changes alias
	function change_alias($alias, $domain, $destination = NULL, $active = NULL) {
		$i = 0;

		if($destination) {
			$query[$i] = "UPDATE alias SET goto = '$destination', modified = NOW()"
			           . " WHERE address = '$alias' AND domain = '$domain'";
			$i++;
		}

		if($active) {
			if($active == 'no') {
				$active = 0;
			} else {
				$active = 1;
			}

			$query[$i] = "UPDATE alias SET active = '$active', modified = NOW()"
			           . " WHERE address = '$alias' AND domain = '$domain'";
			$i++;
		}

		for($n = 0; $n < count($query); $n++)
			$this->ask_db($query[$n]);

		return (TRUE);
	}

	#
	# function lists aliases
	function list_alias($domain = NULL, $alias = NULL) {
		$query = "SELECT * FROM alias WHERE domain = '$domain' AND address <> goto";
		$pages = $this->make_pages($query, 'list=alias&domain='. $this->domain);

		# generate the pagination
		$pages = $this->make_pages($query, 'list=alias&domain='. $this->domain);

		# list specific alias ?
		if($alias) {
			if(!$domain) {
				$query = "SELECT * FROM alias WHERE address = '$alias'";
			} else {
				$query .= " AND address = '$alias'";
			}
		}

		if($this->priv_level == $this->priv_domain_admin) {
			$query .= " AND domain IN"
				. " (SELECT domain FROM domain_admins WHERE username = '$this->auth_user')";
		}

		# add page limits
		$offset = $this->page * $this->config['limit'] - $this->config['limit'];
		$query .= " LIMIT $offset, ". $this->config['limit'];

		$this->ask_db($query);

		if($this->num_rows > 0) {
			$this->alias_no = $this->num_rows;
			$this->alias_list = "";
			$this->alias_list[0]['pages'] = $pages['links'];

			$i = 0;
			while($row = $this->fetch_result()) {
				$this->alias_list[$i]['address'] = $row['address'];
				$this->alias_list[$i]['goto'] = $row['goto'];
				$this->alias_list[$i]['domain'] = $row['domain'];
				$this->alias_list[$i]['created'] = $row['created'];
				$this->alias_list[$i]['modified'] = $row['modified'];
				$this->alias_list[$i]['active'] = $row['active'];

				$i++;
			}
			$this->alias_list[0]['no'] = $pages['row_no'];
			return ($this->alias_list);
		}
		return (FALSE);
	}

	#
	# function deletes alias
	function delete_alias($alias) {
		$query = "DELETE FROM alias WHERE address = '$alias'";
		if($this->priv_level == $this->priv_domain_admin)
			$query = " AND domain IN"
			       . " (SELECT domain FROM domain_admins WHERE username = '$this->auth_user')";
		$this->ask_db($query);

		if(!$this->check_alias($alias))
			return (TRUE);

		return (FALSE);
	}

#############################
#
# Misc functions
#
#############################

	#
	# Function transforms quota to a user friendly format
	function nice_quota($quota) {
		if ($quota > 999999) {
			$nice_quota['value'] = round(($quota / 1000000), 2);
			$nice_quota['quota'] = 'GB';
		} elseif ($quota > 999) {
			$nice_quota['value'] = round(($quota / 1000), 2);
			$nice_quota['quota'] = 'MB';
		} else {
			$nice_quota['value'] = $quota;
			$nice_quota['quota'] = 'KB';
		}
		return ($nice_quota);
	}

	#
	# Function creates pages
	function make_pages($query, $uri) {
		$this->ask_db($query);
		$pagination['row_no'] = $this->num_rows;

		if($this->page != 1) {
			$prev = ($this->page - 1);
			$pagination['links'] = "<a href=\"". $_SERVER['PHP_SELF'] ."?". $uri ."&page=". $prev ."\" >&laquo;</a> ";
		} else {
			$pagination['links'] = "<span class=\"nc\">&laquo;</span>";
		}

		$page_num = $this->num_rows / $this->config['limit'];

		for($i = 1; $i <= $page_num; $i++) {
			if($i == $this->page) {
				$pagination['links'] .= "<a class=\"number\">". $i ."</a> ";
			} else {
				$pagination['links'] .= "<a href=\"". $_SERVER['PHP_SELF'] ."?". $uri ."&page=". $i ."\">$i</a> ";
			}
		}

		if(($this->num_rows % $this->config['limit']) != 0) {
			if($i == $this->page) {
				$pagination['links'] .= "<a class=\"number\">". $i ."</a> ";
			} else {
				$pagination['links'] .= "<a href=\"". $_SERVER['PHP_SELF'] ."?". $uri ."&page=". $i ."\">$i</a> ";
			}
		}

		if(($this->num_rows - $this->config['limit'] * $this->page) > 0) {
			$next = ($this->page + 1);
			$pagination['links'] .= "<a href=\"". $_SERVER['PHP_SELF'] ."?". $uri ."&page=". $next ."\">&raquo;</a>";
		} else {
			$pagination['links'] .= "<span class=\"nc\">&raquo;</span>";
		}
		return ($pagination);
	}

#############################
#
# LOGGING functions
#
#############################

	#
	#
	function write_log($username, $domain, $action, $data) {
		$query = "INSERT INTO log (username, domain, action, data, timestamp)"
		       . " VALUES ('$username', '$domain', '$action', '$data', NOW())";

		$this->ask_db($query);

		if($this->num_rows > 0)
			return (TRUE);

		return (FALSE);
	}

	#
	#
	function read_log() {
		$query = "SELECT * FROM log";

		# is user domain admin ?
		if($this->priv_level == $this->priv_domain_admin)
			$query .= " WHERE domain IN"
			        . " (SELECT domain FROM domain_admins WHERE username='$this->auth_user')";
		if($this->priv_level == $this->priv_user)
			$query .= " WHERE username = '$this->auth_user'";

		$query .= " ORDER BY timestamp DESC";

		# generate the pagination
		$pages = $this->make_pages($query, 'list=logs');

		# add page limits
		$offset = $this->page * $this->config['limit'] - $this->config['limit'];
		$query .= " LIMIT $offset, ". $this->config['limit'];

		$this->ask_db($query);

		if($this->num_rows > 0) {
			$this->log_no = $this->num_rows;
			$this->logs = "";
			$this->logs[0]['pages'] = $pages['links'];

			$i = 0;
			while($row = $this->fetch_result()) {
				$this->logs[$i]['username'] = $row['username'];
				$this->logs[$i]['domain'] = $row['domain'];
				$this->logs[$i]['action'] = $row['action'];
				$this->logs[$i]['data'] = $row['data'];
				$this->logs[$i]['timestamp'] = $row['timestamp'];

				$i++;
			}
			$this->log_no = $pages['row_no'];
			return ($this->logs);
		}
		return (FALSE);
	}

	#
	#
	function purge_log() {
		$query = "DELETE FROM log";

		# is user domain admin ?
		if($this->priv_level == $this->priv_domain_admin)
			$query .= " WHERE domain IN"
			        . " (SELECT domain FROM domain_admins WHERE username='$this->auth_user')";

		$this->ask_db($query);

		if(!$this->read_log())
			return (TRUE);

		return (FALSE);
	}

	#
	#
	function search($keyword, $type) {
		if ($type == 'domains')
			$query = "SELECT * FROM domain WHERE domain LIKE '%$keyword%'";
		if ($type == 'users')
			$query = "SELECT * FROM mailbox WHERE username LIKE '%$keyword%@%'";
		if ($type == 'aliases')
			$query = "SELECT * FROM alias WHERE address LIKE '%$keyword%@%'";

		if($this->priv_level != $this->priv_admin)
			$query .= " AND domain IN"
			        . " (SELECT domain FROM domain_admins WHERE username = '$this->auth_user')";

		$pages = $this->make_pages($query, 'search='. $type .'&keyword='. $keyword);

		$offset = $this->page * $this->config['limit'] - $this->config['limit'];
		$query .= " LIMIT $offset, ". $this->config['limit'];

		$this->ask_db($query);

		if ($this->num_rows > 0) {
			$i = 0;
			while ($row = $this->fetch_result()) {
				if ($type == 'domains')
					$search_list[$i] = $row['domain'];
				if ($type == 'users') {
					$search_list[$i]['domain'] = $row['domain'];
					$search_list[$i]['username'] = $row['username'];

				}
				if ($type == 'aliases') {
					$search_list[$i]['domain'] = $row['domain']; 
					$search_list[$i]['address'] = $row['address'];
				}

				$i++;
			}

			$this->page = 1;
			for ($i = 0; $i < count($search_list); $i++) {
				if ($type == 'domains')
					$result[$i] = $this->list_domains($search_list[$i]);
				if ($type == 'users')
					$result[$i] = $this->list_domain_users($search_list[$i]['domain'], 
					                                       $search_list[$i]['username']);
				if ($type == 'aliases')
					$result[$i] = $this->list_alias($search_list[$i]['domain'], 
					                                $search_list[$i]['address']);
			}

			for ($i = 0; $i < count($search_list); $i++)
				$search_list[$i] = $result[$i][0];

			$search_list[0]['pages'] = $pages['links'];
			$search_list[0]['no'] = $pages['row_no'];
			return ($search_list);
		}
		return (FALSE);
	}

	function get_stats() {
		if ($this->priv_level > $this->priv_user) {		
			$query[0] = "SELECT COUNT(*) AS domains FROM domain";
			$query[1] = "SELECT IFNULL(SUM(maxquota), '0 KB') AS quota FROM domain";
			$query[2] = "SELECT COUNT(*) AS users FROM mailbox";
			$query[3] = "SELECT COUNT(*) AS aliases FROM alias";
			$query[4] = "SELECT COUNT(*) AS logs FROM log";

			if ($this->priv_level == $this->priv_domain_admin) {
				for($i = 0; $i < count($query); $i++) {
					$query[$i] .= " WHERE domain IN"
					           .  " (SELECT domain FROM domain_admins WHERE username = '$this->auth_user')";
				}
			} else {
				$query[5] = "SELECT COUNT(*) AS admins FROM admin";
			}

			for($i = 0; $i < count($query); $i++) {

				$this->ask_db($query[$i]);

				if ($this->num_rows > 0) {
					while ($row = $this->fetch_result()) {
						foreach($row as $name => $count)
							$stats[$name] = $count;
					}
				}
			}

			$quota = $this->nice_quota($stats['quota']);
			$stats['quota'] = $quota['value'] . $quota['quota'];
			return ($stats);

		} else {
			$user = $this->list_domain_users();
			$stats['quota'] = $user[0]['nice_quota']['value'] ." ". $user[0]['nice_quota']['quota'];
			$stats['domain'] = $user[0]['domain'];
			$stats['created'] = $user[0]['created'];
			$stats['modified'] = $user[0]['modified'];
			$stats['username'] = $user[0]['username'];
			$stats['name'] = trim($user[0]['name']);

			$query = "SELECT COUNT(*) AS logs FROM log WHERE username = '$this->auth_user'";
			$this->ask_db($query);
			if ($this->num_rows > 0) {
				while ($row = $this->fetch_result())
					$stats['logs'] = $row['logs'];
			}

			return ($stats);
		}
		return (FALSE);
	}
}
?>
