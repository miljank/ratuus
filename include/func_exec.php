<?

class func_exec {
	function reload() {
		header("Location: ". $_SERVER["REQUEST_URI"]);
		exit;
	}

	function get_stats() {
		global $mail, $ssmarty;

		if(!$stats = $mail->get_stats()) {
			$mail->error('No stats here.');
			return (FALSE);
		}
		$ssmarty->show_list('main', $stats);
		return (TRUE);
	}

	function delete_domain() {
		global $mail, $ssmarty;

		if(!$mail->is_allowed('delete_domain')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->domain) {
			$mail->error('Domain name is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->check_domain($mail->domain)) {
			$mail->error('Domain '. $mail->domain .' does not exist.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->delete_domain($mail->domain)) {
			$mail->error('Could not delete domain '. $mail->domain .'.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		$mail->write_log($mail->auth_user,
		                 $mail->domain,
		                 'delete domain',
		                 $mail->domain);
		# this should be $message = array(1 => 'Domain '. $mail->domain .' deleted.');
		# and than $mail->message(1);
		# and ssmarty (message) print $message[$message];
		$mail->message('Domain '. $mail->domain .' deleted.');
		return (TRUE);
	}

	function add_domain() {
		global $mail, $ssmarty;

		if(!$mail->is_allowed('add_domain')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->domain) {
			$mail->error('Domain name is not set.');
			$mail->text_holder('Please check your request');
			return (FALSE);
		}

		if($mail->check_domain($mail->domain)) {
			$mail->error('Domain '. $mail->domain .' already exists.');
			$mail->text_holder('Please check your request');
			return (FALSE);
		}

		$mail->maxquota = ($mail->quota * $mail->quota_value);
		if(!$mail->add_domain($mail->domain,
		                      $mail->description,
		                      $mail->aliases,
		                      $mail->mailboxes,
		                      $mail->maxquota,
		                      $mail->transport,
		                      $mail->backupmx,
		                      $mail->active)) {
			$mail->error('Could not add domain '. $mail->domain .'.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		$mail->write_log($mail->auth_user,
		                 $mail->domain,
		                 'add domain',
		                 $mail->domain);
		$mail->message('New domain '. $mail->domain .' has been added.');
		return (TRUE);
	}

	function change_domain() {
		global $mail, $ssmarty;

		if(!$mail->is_allowed('change_domain')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->domain) {
			$mail->error('Domain name is not set.');
			$mail->text_holder('Please check your request');
			return (FALSE);
		}

		if(!$mail->active) {
			$mail->error('Active value is not set.');
			$mail->text_holder('Please check your request');
			return (FALSE);
		}

		if(!$mail->check_domain($mail->domain)) {
			$mail->error('Domain '. $mail->domain .' does not exists.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		$mail->maxquota = ($mail->quota * $mail->quota_value);
		if(!$mail->change_domain($mail->domain,
		                         $mail->description,
		                         $mail->aliases,
		                         $mail->maxquota,
		                         $mail->mailboxes,
		                         $mail->transport,
		                         $mail->backupmx,
		                         $mail->active)) {
			$mail->error('Domain '. $mail->domain .' could not be modified.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		$mail->write_log($mail->auth_user,
		                 $mail->domain,
		                 'modify domain',
		                 $mail->domain);
		$mail->message('Domain '. $mail->domain .' is modified.');
		return (TRUE);
	}

	function list_domains() {
		global $mail, $ssmarty;

		if(!$mail->is_allowed('list_domains')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$domain = $mail->list_domains()) {
			$ssmarty->text_holder('There are no domains here yet!');
			$mail->notice('You can add domains by clicking on "Add Domain" link.');
			return (FALSE);
		}

		$ssmarty->show_list('domains', $domain);
		return (TRUE);
	}

	function add_user() {
		global $mail, $ssmarty;

		if(!$mail->is_allowed('add_user')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->domain) {
			$mail->error('Domain name is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->username) {
			$mail->error('Username is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->password) {
			$mail->error('Password is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if($mail->password != $mail->password_2) {
			$mail->error('Password doesn\'t match.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if($mail->priv_level == $mail->priv_domain_admin &&
		   !$mail->is_domain_admin($mail->auth_user, $mail->domain)) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->check_domain($mail->domain)) {
			$mail->error('Domain '. $mail->domain .' does not exists.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if($mail->check_user($mail->username, $mail->domain)) {
			$mail->error('User '. $mail->username .'@'. $mail->domain .' already exists.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		$mail->quota = ($mail->quota * $mail->quota_value);
		if(!$mail->add_user($mail->username,
		                    $mail->password,
		                    $mail->name,
		                    $mail->quota,
		                    $mail->domain,
		                    $mail->active)) {
			//$mail->error('System was not successful in performing this action. Please try again latter.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		$mail->write_log($mail->auth_user,
		                 $mail->domain,
		                 'add user',
		                 $mail->username);
		$mail->message('User '. $mail->username .'@'. $mail->domain .' was successfully added.');
		return (TRUE);
	}

	function change_user() {
		global $mail, $ssmarty;

		if(!$mail->is_allowed('change_user')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->username) {
			$mail->error('Username is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->quota) {
			$mail->error('Quota value is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->active) {
			$mail->error('Active value is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if($mail->priv_level == $mail->priv_domain_admin &&
		   !$mail->is_domain_admin($mail->auth_user, $mail->domain)) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if($mail->priv_level == $mail->priv_user &&
		  !$mail->username == $mail->auth_user) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->check_user($mail->username)) {
			$mail->error('User '. $mail->username .' does not exists.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		$mail->quota = ($mail->quota * $mail->quota_value);
		if(!$mail->change_user($mail->username,
		                       $mail->name,
		                       $mail->password,
		                       $mail->quota,
		                       $mail->active)) {
			//$mail->error('User '. $mail->username .' could not be modified.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		$mail->write_log($mail->auth_user,
		                 $mail->domain,
		                 'modify user',
		                 $mail->username);
		$mail->message('User '. $mail->username .' was successfully modified.');
		return (TRUE);
	}

	function delete_user() {
		global $mail, $ssmarty;

		if(!$mail->is_allowed('delete_user')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->domain) {
			$mail->error('Domain name is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->username) {
			$mail->error('Username is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if($mail->priv_level == $mail->priv_domain_admin &&
		   !$mail->is_domain_admin($mail->auth_user, $mail->domain)) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->check_user($mail->username) && $mail->check_domain($mail->domain)) {
			$mail->error('User '. $mail->username .' does not exists.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->delete_user($mail->username, $mail->domain)) {
			$mail->error('User '. $mail->username .' could not be deleted.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		$mail->write_log($mail->auth_user,
		                 $mail->domain,
		                 'delete user',
		                 $mail->username);
		$mail->message('User '. $mail->username .' deleted.');
		return (TRUE);
	}

	function list_users() {
		global $mail, $ssmarty;

		if(!$mail->is_allowed('list_domain_users')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->domain) {
			$mail->error('Domain name is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if($mail->priv_level == $mail->priv_domain_admin &&
		   !$mail->is_domain_admin($mail->auth_user, $mail->domain)) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if($mail->priv_level == $mail->priv_user &&
		   !$mail->username == $mail->auth_user) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->check_domain($mail->domain)) {
			$mail->error('Domain '. $mail->domain .' does not exists.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$user = $mail->list_domain_users($mail->domain)) {
			$mail->text_holder('Domain '. $mail->domain .' does not have any users defined.');
			$mail->notice('You can add new user by clicking on "Add User" link.');
			return (FALSE);
		}

		$ssmarty->show_list('users', $user);
		return (TRUE);
			
	}

	function add_alias() {
		global $mail, $ssmarty;

		if(!$mail->is_allowed('add_alias')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if($mail->priv_level == $mail->priv_domain_admin &&
		   !$mail->is_domain_admin($mail->auth_user, $mail->domain)) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->domain) {
			$mail->error('Domain name is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->alias) {
			$mail->error('Alias value is not set');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->destination) {
			$mail->error('Alias destination is not set');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->check_domain($mail->domain)) {
			$mail->error('Domain '. $mail->domain .' does not exists.');
			$mail->text_holder('Please check your request');
			return (FALSE);
		}

		$mail->alias = $mail->alias .'@'. $mail->domain;
		if($mail->check_user($mail->alias)) {
			$mail->error($mail->alias .' already exists as a real mail account.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if($mail->check_alias($mail->alias)) {
			$mail->error('Alias '. $mail->alias .' already exists.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->add_alias($mail->alias, $mail->domain, $mail->destination)) {
			//$mail->error('Failed to add alias '. $mail->alias .' for '. $mail->destination .'.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		$mail->write_log($mail->auth_user,
		                 $mail->domain,
		                 'add alias',
		                 $mail->alias .' > '. $mail->destination);
		$mail->message('Alias '. $mail->alias .' for '. $mail->destination .' added.');
		return (TRUE);
	}

	function change_alias() {
		global $mail, $ssmarty;

		if(!$mail->is_allowed('change_alias')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if($mail->priv_level == $mail->priv_domain_admin &&
		   !$mail->is_domain_admin($mail->auth_user, $mail->domain)) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->domain) {
			$mail->error('Domain name is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->alias) {
			$mail->error('Alias value is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->destination) {
			$mail->error('Alias destination is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->active) {
			$mail->error('Active value is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->check_domain($mail->domain)) {
			$mail->error('Domain '. $mail->domain .' does not exists.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->check_alias($mail->alias)) {
			$mail->error('Alias '. $mail->alias .' does not exists.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->change_alias($mail->alias,
		                        $mail->domain,
		                        $mail->destination,
		                        $mail->active)) {
			$mail->error('Failed to change alias '. $mail->alias .'.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		$mail->write_log($mail->auth_user,
		                 $mail->domain,
		                 'modify alias',
		                 $mail->alias .' > '. $mail->destination);
		$mail->message('Alias '. $mail->alias .' changed.');
		return (TRUE);
	}

	function delete_alias() {
		global $mail, $ssmarty;

		if(!$mail->is_allowed('delete_alias')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if($mail->priv_level == $mail->priv_domain_admin &&
		   !$mail->is_domain_admin($mail->auth_user, $mail->domain)) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->alias) {
			$mail->error('Alias value is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->check_alias($mail->alias)) {
			$mail->error('Alias '. $mail->alias .' does not exists.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->delete_alias($mail->alias)) {
			$mail->error('Alias '. $mail->alias .' could not be deleted.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		$mail->write_log($mail->auth_user,
		                 $mail->domain,
		                 'delete alias',
		                 $mail->alias);
		$mail->message('Alias '. $mail->alias .' deleted.');
		return (TRUE);
	}

	function list_alias() {
		global $mail, $ssmarty;

		if(!$mail->is_allowed('list_alias')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if($mail->priv_level == $mail->priv_domain_admin &&
		   !$mail->is_domain_admin($mail->auth_user, $mail->domain)) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->domain) {
			$mail->error('Domain name is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->check_domain($mail->domain)) {
			$mail->error('Domain '. $mail->domain .' does not exist.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$alias = $mail->list_alias($mail->domain)) {
			$mail->text_holder('Domain '. $mail->domain .' does not have any aliases defined.');
			$mail->notice('You can add new alias by clicking on "Add Alias" link.');
			return (FALSE);
		}

		$ssmarty->show_list('aliases', $alias);
		return (TRUE);
	}

	function add_admin() {
		global $mail, $ssmarty;

		if(!$mail->is_allowed('add_admin')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->username) {
			$mail->error('Username is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->password) {
			$mail->error('Password is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if($mail->check_user($mail->username) || $mail->is_admin($mail->username)) {
			$mail->error('Admin '. $mail->username .' already exists.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->add_admin($mail->username, $mail->password, $mail->active)) {
			$mail->error('Admin '. $mail->username .' could not be added.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		$mail->write_log($mail->auth_user,
		                 'admin',
		                 'add admin',
		                 $mail->username);
		$mail->message('Admin '. $mail->username .' successfully added.');
		return (TRUE);
	}

	function change_admin() {
		global $mail, $ssmarty;

		if(!$mail->is_allowed('change_admin')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->username) {
			$mail->error('Username is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->active) {
			$mail->error('Active value is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->list_admins($mail->username)) {
			$mail->error('Admin '. $mail->username .' does not exists.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->change_admin($mail->username, $mail->active, $mail->password)) {
			$mail->error('Admin '. $mail->username .' could not be modified or no modification needed.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		$mail->write_log($mail->auth_user,
		                 'admin',
		                 'modify admin',
		                 $mail->username);
		$mail->message('Admin '. $mail->username .' modified.');
		return (TRUE);
	}

	function delete_admin() {
		global $mail, $ssmarty;

		if(!$mail->is_allowed('delete_admin')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->username) {
			$mail->error('Username is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->is_admin($mail->username)) {
			$mail->error('Admin '. $mail->username .' does not exists.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->delete_admin($mail->username)) {
			$mail->error('Admin '. $mail->username .' could not be deleted.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		$mail->write_log($mail->auth_user,
		                 'admin',
		                 'delete admin',
		                 $mail->username);
		$mail->message('Admin '. $mail->username .' deleted.');
		return (TRUE);
	}

	function list_admins() {
		global $mail, $ssmarty;

		if(!$mail->is_allowed('list_admins')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$admins = $mail->list_admins()) {
			$mail->error('There are no admin users defined. You are lost now!!! :o)');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		$ssmarty->show_list('admins', $admins);
		return (TRUE);
	}

	function add_config() {
		global $mail, $ssmarty;

		if(!$mail->is_allowed('add_config')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->config_opt) {
			$mail->error('Option name is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->config_value) {
			$mail->error('Option value is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if($mail->get_config($mail->config_opt)) {
			$mail->error('Option '. stripslashes($mail->config_opt) .' already exists.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->add_config($mail->config_opt, $mail->config_value)) {
			$mail->error('Option '. stripslashes($mail->config_opt) .' could not be added.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		$mail->write_log($mail->auth_user,
		                 'admin',
		                 'add config option',
		                 $mail->config_opt .': '. $mail->config_value);
		$mail->message('Option '. stripslashes($mail->config_opt) .' added.');
		return (TRUE);
	}

	function change_config() {
		global $mail, $ssmarty;

		if(!$mail->is_allowed('change_config')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->config_opt) {
			$mail->error('Option name is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->config_value) {
			$mail->error('Option value is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->get_config($mail->config_opt)) {
			$mail->error('Option '. stripslashes($mail->config_opt) .' does not exists.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->change_config($mail->config_opt, $mail->config_value)) {
			$mail->error('Could not change option '. stripslashes($mail->config_opt) .'.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		$mail->write_log($mail->auth_user,
		                 'admin',
		                 'modify configuration',
		                 $mail->config_opt .': '. $mail->config_value);
		print $mail->config_value;
		# $mail->message('Option '. stripslashes($mail->config_opt) .' changed.');
		return (TRUE);
	}

	function delete_config() {
		global $mail, $ssmarty;

		if(!$mail->is_allowed('delete_config')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->config_opt) {
			$mail->error('Option name is not set.');
			$mail->text_holder('Please check your request..');
			return (FALSE);
		}

		if(!$mail->get_config($mail->config_opt)) {
			$mail->error('Option '. stripslashes($mail->config_opt) .' does not exists.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->delete_config($mail->config_opt)) {
			$mail->error('Could not delete option '. stripslashes($mail->config_opt) .'.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		$mail->write_log($mail->auth_user,
		                 'admin',
		                 'delete config option',
		                 $mail->config_opt);
		$mail->message('Option '. stripslashes($mail->config_opt) .' deleted.');
		return (TRUE);
	}

	function list_config() {
		global $mail, $ssmarty;

		if(!$mail->is_allowed('delete_config')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		unset($mail->config);
		if(!$config = $mail->get_config()) {
			$mail->get_config();
			$mail->error('There are no settings here. What have you done?! :o)');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		$user_quota = $mail->nice_quota($config['user_quota']);
		$domain_quota = $mail->nice_quota($config['domain_quota']);
		$config['user_quota'] = $user_quota['value'] .' '. $user_quota['quota'];
		$config['domain_quota'] = $domain_quota['value'] .' '. $domain_quota['quota'];
		$ssmarty->show_list('config', $config);
		$mail->get_config();
		return (TRUE);

	}

	function purge_logs() {
		global $mail, $ssmarty;

		if(!$mail->is_allowed('purge_log')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->purge_log()) {
			$mail->error('Could not delete logs.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		$mail->write_log($mail->auth_user,
		                 'admin',
		                 'delete logs',
		                 'Logs deleted.');
		$mail->message('Logs were successfully deleted.');
		return (TRUE);
	}

	function read_log() {
		global $mail, $ssmarty;

		if(!$mail->is_allowed('read_log')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$log = $mail->read_log()) {
			$mail->notice('Logs will be generated automatically.');
			$mail->text_holder('There are no logs here.');
			return (FALSE);
		}

		$ssmarty->show_list('logs', $log);
		return (TRUE);
	}

	function change_password() {
		global $mail, $ssmarty;

		if(!$mail->is_allowed('change_password')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if($mail->priv_level == $mail->priv_user &&
		   !$mail->username == $mail->auth_user) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if(!$mail->username) {
			$mail->error('Username is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->check_user($mail->username)) {
			$mail->error('User '. $mail->username .' does not exists.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if(!$mail->change_user($mail->username, $mail->name, $mail->password)) {
			$mail->error('User '. $mail->username .' could not be modified.');
			$mail->text_holder('Please check your request..');
			return (FALSE);
		}

		$mail->write_log($mail->auth_user,
		                 $mail->domain,
		                 'change password',
		                 $mail->username);
		$mail->message('User '. $mail->username .' was successfully modified.');
		return (TRUE);
	}


	function search() {
		global $mail, $ssmarty;

		if (!$mail->is_allowed('search')) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if (!$mail->keyword) {
			$mail->error('Keyword is not set.');
			$mail->text_holder('Please check your request.');
			return (FALSE);
		}

		if (!$search = $mail->search($mail->keyword, $mail->search)) {
			$ssmarty->text_holder('No results!');
			$mail->notice('You can try to broaden your search.');
			return (FALSE);
		}

		$ssmarty->show_list($mail->search, $search);
		return (TRUE);
	}

	function show_form($form) {
		global $mail, $ssmarty;

		if(!$mail->is_allowed($form)) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);	
		}

		if($mail->priv_level == $mail->priv_domain_admin &&
		   !$mail->is_domain_admin($mail->auth_user, $mail->domain)) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		if($mail->priv_level == $mail->priv_user &&
		   !$mail->username == $mail->auth_user) {
			$mail->error('You are not authorized to do this.');
			$mail->text_holder('Please consult your system administrator.');
			return (FALSE);
		}

		switch ($form) {
			case 'add_domain':
				$quota = $mail->nice_quota($mail->config['domain_quota']);
				$ssmarty->show_form('add_domain', $quota);
				$ssmarty->smarty_file('domains.tpl');
				break;
			case 'change_domain':
				if(!$mail->domain) {
					$mail->error('Domain name is not set.');
					$mail->text_holder('Please check your request.');
					return (FALSE);
				}

				if(!$mail->check_domain($mail->domain)) {
					$mail->error('Domain '. $mail->domain .' does not exists.');
					$mail->text_holder('Please check your request.');
					return (FALSE);
				}

				$domain = $mail->list_domains($mail->domain);
				$ssmarty->show_form('change_domain', $domain);
				$ssmarty->smarty_file('domains.tpl');
				break;
			case 'add_user':
				if(!$mail->domain) {
					$mail->error('Domain name is not set.');
					$mail->text_holder('Please check your request.');
					return (FALSE);
				}

				if(!$mail->check_domain($mail->domain)) {
					$mail->error('Domain '. $mail->domain .' does not exists.');
					$mail->text_holder('Please check your request.');
					return (FALSE);
				}

				$quota = $mail->nice_quota($mail->config['user_quota']);
				$ssmarty->show_form('add_user', $quota);
				$ssmarty->smarty_file('users.tpl');
				break;
			case 'change_user':
/*				if(!$mail->domain) {
					$mail->error('Domain name is not set.');
					$mail->text_holder('Please check your request.');
					return (FALSE);
				}
 */
				if(!$mail->username) {
					$mail->error('Username is not set');
					$mail->text_holder('Please check your request.');
					return (FALSE);
				}

/*				if(!$mail->check_domain($mail->domain)) {
					$mail->error('Domain '. $mail->domain .' does not exists.');
					$mail->text_holder('Please check your request.');
					return (FALSE);
 				}
*/
				if(!$mail->check_user($mail->username)) {
					$mail->error('User '. $mail->username .' does not exists.');
					$mail->text_holder('Please check your request.');
					return (FALSE);
				}

				$user = $mail->list_domain_users($mail->domain, $mail->username);
				$ssmarty->show_form('change_user', $user);
				$ssmarty->smarty_file('users.tpl');
				break;
			case 'add_alias':
				if(!$mail->domain) {
					$mail->error('Domain name is not set.');
					$mail->text_holder('Please check your request.');
					return (FALSE);
				}

				if(!$mail->check_domain($mail->domain)) {
					$mail->error('Domain '. $mail->domain .' does not exists.');
					$mail->text_holder('Please check your request.');
					return (FALSE);
				}

				$ssmarty->show_form('add_alias');
				$ssmarty->smarty_file('aliases.tpl');
				break;
			case 'change_alias':
/*				if(!$mail->domain) {
					$mail->error('Domain name is not set.');
					$mail->text_holder('Please check your request.');
					return (FALSE);
				}
 */
				if(!$mail->alias) {
					$mail->error('Alias value is not set.');
					$mail->text_holder('Please check your request.');
					return (FALSE);
				}

/*				if(!$mail->check_domain($mail->domain)) {
					$mail->error('Domain '. $mail->domain .' does not exists.');
					$mail->text_holder('Please check your request.');
					return (FALSE);
				}
 */
				if(!$mail->check_alias($mail->alias)) {
					$mail->error('Alias '. $mail->alias .' does not exists.');
					$mail->text_holder('Please check your request.');
					return (FALSE);
				}

				$alias = $mail->list_alias($mail->domain, $mail->alias);
				$ssmarty->show_form('change_alias', $alias);
				$ssmarty->smarty_file('aliases.tpl');
				break;
			case 'add_admin':
				$ssmarty->show_form('add_admin');
				$ssmarty->smarty_file('admins.tpl');
				break;
			case 'change_admin':
				if(!$mail->username) {
					$mail->error('Username is not set.');
					$mail->text_holder('Please check your request.');
					return (FALSE);
				}

				if(!$admin = $mail->list_admins($mail->username)) {
					$mail->error('Admin '. $mail->username .' does not exists.');
					$mail->text_holder('Please check your request.');
					return (FALSE);
				}

				$ssmarty->show_form('change_admin', $admin);
				$ssmarty->smarty_file('admins.tpl');
				break;
			case 'add_config':
				$ssmarty->show_form('add_config');
				$ssmarty->smarty_file('config.tpl');
				break;
			case 'change_config':
				if(!$mail->config_opt) {
					$mail->error('Option name is not set.');
					$mail->text_holder('Please check your request.');
					return (FALSE);
				}

				unset($mail->config);
				if(!$n_config = $mail->get_config($mail->config_opt)) {
					$mail->error('Option '. stripslashes($mail->config_opt) .' does not exists.');
					$mail->text_holder('Please check your request.');
					return (FALSE);
				}

				$ssmarty->show_form('change_config', $n_config);
				$mail->get_config();
				$ssmarty->smarty_file('config.tpl');
				break;
			case 'change_password':
/*
				if(!$mail->domain) {
					$mail->error('Domain name is not set.');
					$mail->text_holder('Please check your request.');
					return (FALSE);
				}
*/

				if(!$mail->username) {
					$mail->error('Username is not set.');
					$mail->text_holder('Please check your request.');
					return (FALSE);
				}

/*
				if(!$mail->check_domain($mail->domain)) {
					$mail->error('Domain '. $mail->domain .' does not exists.');
					$mail->text_holder('Please check your request.');
					return (FALSE);
				}
*/

				if(!$user = $mail->list_domain_users($mail->domain, $mail->username)) {
					$mail->error('User '. $mail->username .' does not exists.');
					$mail->text_holder('Please check your request.');
					return (FALSE);
				}

				$ssmarty->show_form('change_password', $user);
				$ssmarty->smarty_file('users.tpl');
				break;
			case 'add_domain_admin':
				if(!$domains = $mail->list_domains()) {
					$mail->error('No domains here yet.');
					return (FALSE);
				}

				if($mail->domain) {
					if(!$users = $mail->list_domain_users($mail->domain)) {
						$mail->error('No users for this domain yet');
					}
				}

				$ssmarty->show_form('add_domain_admins', $domains, $users);
				$ssmarty->smarty_file('domain_admins.tpl');
				break;
		}

		return (TRUE);
	}
}

?>
