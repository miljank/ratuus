<?

#
# get local path
define('WEB_FOLDER_PATH', dirname(__FILE__));

#
# Includes configuration
require_once(WEB_FOLDER_PATH . "/include/config.php");

/* ==========================================LOGIN START =================================== */

if($mail->action == 'logout') {
	if($mail->check_login()) {
		$mail->do_logout();
		header("Location: http://". $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
	} else {
		$mail->error('You are not logged in at the moment!');
	}
}

if(!$mail->check_login()) {
	if(!$mail->login || !$mail->do_login()) {
		if($mail->login) {
			$mail->error('You have entered wrong credentials.');
			$mail->text_holder('Please consult your system administrator.');
		}

		$smarty->assign('session', 'start');
		$smarty->display('all/login.tpl');
		die;
	}
}

if($mail->login)
	$exec->reload();

if($mail->list == 'main' || empty($_GET)) {
	$exec->get_stats();
	$ssmarty->smarty_file('main.tpl');
}

switch($mail->priv_level) {
	case $mail->priv_admin:
		/*
		**
		** user is admin
		**
		*/

		##### DOMAIN functions ####

		# delete domain
		if($mail->delete == 'domain') {
			$exec->delete_domain();
			$mail->list = 'domains';
		}

		# print addDomain form
		if($mail->form == 'addDomain') {
			if(!$exec->show_form('add_domain')) 
				$mail->list = 'domains';
		}

		# add new domain
		if($mail->add == 'domain') {
			$exec->add_domain();
			$exec->reload();
			$mail->list = 'domains';
		}

		# print changeDomain form
		if($mail->form == 'changeDomain') {
			if(!$exec->show_form('change_domain'))
				$mail->list = 'domains';
		}

		# change domain
		if($mail->change == 'domain') {
			$exec->change_domain();
			$exec->reload();
			$mail->list = 'domains';
		}

		# list domains
		if($mail->list == 'domains') {
			$smarty->assign('page', 'domains');
			$exec->list_domains();
			//$ssmarty->show_message();
			$ssmarty->smarty_file('domains.tpl');
		}

		##### USER functions #####

		# print addUser form
		if($mail->form == 'addUser') {
			if(!$exec->show_form('add_user'))
				$mail->list = 'users';
		}

		# add user
		if($mail->add == 'user') {
			$exec->add_user();
			$exec->reload();
			$mail->list = 'users';
		}

		# print changeUser form
		if($mail->form == 'changeUser') {
			if(!$exec->show_form('change_user'))
				$mail->list = 'users';
		}

		# modifies user
		if($mail->change == 'user') {
			$exec->change_user();
			$exec->reload();
			$mail->list = 'users';
		}

		# delete user
		if($mail->delete == 'user') {
			$exec->delete_user();
			$mail->list = 'users';
		}

		# list users
		if($mail->list == 'users') {
			$exec->list_users();
			$smarty->assign('page', 'domains');
			$smarty->assign('domain', $mail->domain);
			//$ssmarty->show_message();
			$ssmarty->smarty_file('users.tpl');
		}

		##### ALIAS functions #####

		# print addAlias form
		if($mail->form == 'addAlias') {
			if(!$exec->show_form('add_alias'))
				$mail->list = 'alias';
		}

		# add alias
		if($mail->add == 'alias') {
			$exec->add_alias();
			$exec->reload();
			$mail->list = 'alias';
		}
	
		# print changeAlias form
		if($mail->form == 'changeAlias') {
			if(!$exec->show_form('change_alias'))
				$mail->list = 'alias';
		}

		# change alias
		if($mail->change == 'alias') {
			$exec->change_alias();
			$exec->reload();
			$mail->list = 'alias';
		}

		# delete alias
		if($mail->delete == 'alias') {
			$exec->delete_alias();
			$mail->list = 'alias';
		}

		# list alias
		if($mail->list == 'alias') {
			$exec->list_alias();
			$smarty->assign('page', 'domains');
			$smarty->assign('domain', $mail->domain);
			//$ssmarty->show_message();
			$ssmarty->smarty_file('aliases.tpl');
		}

		##### DOMAIN ADMIN function #####

		# print addDomain form
		if($mail->form == 'addDomainAdmin') {
			if(!$exec->show_form('add_domain_admin'))
				$mail->list = 'domains';
		}

		##### ADMIN function #####

		# print addAdmin form
		if($mail->form == 'addAdmin') {
			if(!$exec->show_form('add_admin'))
				$mail->list = 'admins';
		}

		# add admin
		if($mail->add == 'admin') {
			$exec->add_admin();
			$exec->reload();
		}

		# print changeAdmin form
		if($mail->form == 'changeAdmin') {
			if(!$exec->show_form('change_admin'))
				$mail->list = 'admins';
		}

		# change admin
		if($mail->change == 'admin') {
			$exec->change_admin();
			$exec->reload();
		}

		# delete admin
		if($mail->delete == 'admin') {
			$exec->delete_admin();
			$mail->list = 'admins';
		}

		# list admins
		if($mail->list == 'admins') {
			$smarty->assign('page', 'admins');
			$exec->list_admins();
			$ssmarty->show_message();
			$ssmarty->smarty_file('admins.tpl');
		}

		##### CONF functions

		# print addConfig form
		if($mail->form == 'addConfig') {
			if(!$exec->show_form('add_config'))
				$mail->list = 'config';
		}

		# add config
		if($mail->add == 'config') {
			$exec->add_config();
			$mail->list = 'config';
		}

		# print changeConfig form
		if($mail->form == 'changeConfig') {
			if(!$exec->show_form('change_config'))
				$mail->list = 'config';
		}

		# change config
		if($mail->change == 'config') {
			$exec->change_config();
	#		$mail->list = 'config';
		}

		# delete config
		if($mail->delete == 'config') {
			$exec->delete_config();
			$mail->list = 'config';
		}

		# list config
		if($mail->list == 'config') {
			$smarty->assign('page', 'configuration');
			$exec->list_config();
			//$ssmarty->show_message();
			$ssmarty->smarty_file('config.tpl');
		}

		##### LOG functions #####

		# delete logs
		if($mail->delete == 'log') {
			$exec->purge_logs();
			$mail->list = 'logs';
		}

		# print logs
		if($mail->list == 'logs') {
			$smarty->assign('page', 'logs');
			$exec->read_log();
			//$ssmarty->show_message();
			$ssmarty->smarty_file('logs.tpl');
		}

		##### SEARCH functions #####
		if($mail->search) {
			$exec->search();
			$smarty->assign('page', 'domains');
			$smarty->assign('search', 1);
			if ($mail->search == 'domains')
				$ssmarty->smarty_file('domains.tpl');
			if ($mail->search == 'users')
				$ssmarty->smarty_file('users.tpl');
			if ($mail->search == 'aliases')
				$ssmarty->smarty_file('aliases.tpl');
		}
				
		break;
	case $mail->priv_domain_admin:
		/*
		**
		** User is domain admin
		**
		*/

		##### DOMAIN functions #####

		# print list of domains
		if($mail->list == 'domains') {
			$exec->list_domains();
			//$ssmarty->show_message();
			$ssmarty->smarty_file('domains.tpl');
		}

		# print addUser form
		if($mail->form == 'addUser') {
			if(!$exec->show_form('add_user'))
				$mail->list = 'users';
		}

		# add user
		if($mail->add == 'user') {
			$exec->add_user();
			$exec->reload();
			$mail->list = 'users';
		}

		# print changeUser form
		if($mail->form == 'changeUser') {
			if(!$exec->show_form('change_user'))
				$mail->list = 'users';
		}

		# change user
		if($mail->change == 'user') {
			$exec->change_user();
			$exec->reload();
			$mail->list = 'users';
		}

		# delete user
		if($mail->delete == 'user') {
			$exec->delete_user();
			$mail->list = 'users';
		}

		# list users
		if($mail->list == 'users') {
			$exec->list_users();
			$smarty->assign('page', 'domains');
			//$ssmarty->show_message();
			$smarty->assign('domain', $mail->domain);
			$ssmarty->smarty_file('users.tpl');
		}

		##### ALIAS functions

		# print addAlias form
		if($mail->form == 'addAlias') {
			if(!$exec->show_form('add_alias'))
				$mail->list = 'alias';
		}

		# add alias
		if($mail->add == 'alias') {
			$exec->add_alias();
			$exec->reload();
			$mail->list = 'alias';
		}

		# print changeAlias form
		if($mail->form == 'changeAlias') {
			if(!$exec->show_form('change_alias'))
				$mail->list = 'alias';
		}

		# change alias
		if($mail->change == 'alias') {
			$exec->change_alias();
			$exec->reload();
			$mail->list = 'alias';
		}

		# delete alias
		if($mail->delete == 'alias') {
			$exec->delete_alias();
			$mail->list = 'alias';
		}

		# list alias
		if($mail->list == 'alias') {
			$smarty->assign('page', 'domains');
			$smarty->assign('domain', $mail->domain);
			$exec->list_alias();
			//$ssmarty->show_message();
			$ssmarty->smarty_file('aliases.tpl');
		}

		##### LOG functions #####

		# delete logs
		if($mail->delete == 'log') {
			$exec->purge_logs();
			$mail->list = 'logs';
		}

		# list logs
		if($mail->list == 'logs') {
			$smarty->assign('page', 'logs');
			$exec->read_log();
			//$ssmarty->show_message();
			$ssmarty->smarty_file('logs.tpl');
		}


		##### SEARCH functions #####
		if($mail->search) {
			$exec->search();
			$smarty->assign('page', 'domains');
			$smarty->assign('search', 1);
			if ($mail->search == 'domains')
				$ssmarty->smarty_file('domains.tpl');
			if ($mail->search == 'users')
				$ssmarty->smarty_file('users.tpl');
			if ($mail->search == 'aliases')
				$ssmarty->smarty_file('aliases.tpl');
		}

		break;

	case $mail->priv_user:
		/*
		**
		** User is regular user
		**
		*/

		# print changeUser form
		if($mail->form == 'changeUser') {
			if(!$exec->show_form('change_password'))
				$mail->list = 'users';
		}

		# change user
		if($mail->change == 'user') {
			$exec->change_password();
			$exec->reload();
			$mail->list = 'users';
		}

		# list user
		if($mail->list == 'users') {
			$exec->get_stats();
			$ssmarty->smarty_file('main.tpl');
		}

		# list logs
		if($mail->list == 'logs') {
			$smarty->assign('page', 'logs');
			$exec->read_log();
			//$ssmarty->show_message();
			$ssmarty->smarty_file('logs.tpl');
		}

		break;
}

# closes DB connection
$mail->db_disconnect();

?>
