<?

class show_smarty {
	function message($message, $status) {
		if($status == 'error')
			$_SESSION['sm_message']['error'] = $message;

		if($status == 'confirmation')
			$_SESSION['sm_message']['confirmation'] = $message;

		if($status == 'notice')
			$_SESSION['sm_message']['notice'] = $message;

		if($status)
			$_SESSION['sm_message']['status'] = $status;
	}

	function text_holder($message) {
		if($message)
			$_SESSION['sm_message']['text_holder'] = $message;
	}

	function show_message() {
		global $smarty;

		if($_SESSION['sm_message']) {
			$smarty->assign('message', $_SESSION['sm_message']);
			$smarty->assign('msg_type', $_SESSION['sm_message']['status']);
			$smarty->assign('text_holder', $_SESSION['sm_message']['text_holder']);
			unset($_SESSION['sm_message']);
		}
	}

	function show_form($form, $param = NULL, $param2 = NULL) {
		global $smarty, $mail;
		switch ($form) {
			case 'add_domain':
				$smarty->assign('form', 'addDomain');
				$smarty->assign('mailboxes', $mail->config['mailboxes']);
				$smarty->assign('aliases', $mail->config['aliases']);
				$smarty->assign('quota_value', $param['value']);
				$smarty->assign('quota', $param['quota']);
				$smarty->assign('transport', $mail->config['transport']);
				$smarty->assign('page', 'domains');
				break;
			case 'change_domain':
				$smarty->assign('form', 'changeDomain');
				$smarty->assign('domain', $param);
				$smarty->assign('page', 'domains');
				break;
			case 'add_user':
				$smarty->assign('form', 'addUser');
				$smarty->assign('domain', $mail->domain);
				$smarty->assign('quota', $param['quota']);
				$smarty->assign('quota_value', $param['value']);
				$smarty->assign('page', 'domains');
				break;
			case 'change_user':
				$smarty->assign('form', 'changeUser');
				$smarty->assign('user', $param);
				$smarty->assign('page', 'domains');
				break;
			case 'change_password':
				$smarty->assign('form', 'changeUser');
				$smarty->assign('user', $param);
				$smarty->assign('page', 'user');
				break;
			case 'add_alias':
				$smarty->assign('form', 'addAlias');
				$smarty->assign('domain', $mail->domain);
				$smarty->assign('page', 'domains');
				break;
			case 'change_alias':
				$smarty->assign('form', 'changeAlias');
				$smarty->assign('alias', $param[0]);
				$smarty->assign('page', 'domains');
				break;
			case 'add_admin':
				$smarty->assign('form', 'addAdmin');
				$smarty->assign('page', 'admins');
				break;
			case 'change_admin':
				$smarty->assign('form', 'changeAdmin');
				$smarty->assign('admin', $param[0]);
				$smarty->assign('page', 'admins');
				break;
			case 'add_domain_admin':
				break;
			case 'change_domain_admin':
				break;
			case 'add_config':
				$smarty->assign('form', 'addConfig');
				$smarty->assign('page', 'configuration');
				break;
			case 'change_config':
				$smarty->assign('form', 'changeConfig');
				$smarty->assign('config', $param);
				$smarty->assign('page', 'configuration');
				break;
			case 'add_domain_admins':
				$smarty->assign('form', 'add_domain_admins');
				$smarty->assign('domains', $param);
				$smarty->assign('users', $param2);
				$smarty->assign('domain', $mail->domain);
				$smarty->assign('page', 'domain');
				break;
		}
	}

	function show_list($type, $param = NULL) {
		global $smarty, $mail;
		switch ($type) {
			case 'main':
				$smarty->assign('stats', $param);
				$smarty->assign('page', 'stats');
				break;
			case 'domains':
				$smarty->assign('list', 'domains');
				$smarty->assign('domains', $param);
				$smarty->assign('domain_no', $mail->domain_no);
				$smarty->assign('page', 'domains');
				break;
			case 'users':
				$smarty->assign('list', 'users');
				$smarty->assign('users', $param);
				$smarty->assign('page', 'domains');
				break;
			case 'aliases':
				$smarty->assign('list', 'alias');
				$smarty->assign('domain', $mail->domain);
				$smarty->assign('alias', $param);
				$smarty->assign('alias_no', $mail->alias_no);
				$smarty->assign('page', 'domains');
				break;
			case 'admins':
				$smarty->assign('list', 'admins');
				$smarty->assign('admins', $param);
				$smarty->assign('admin_no', $mail->admin_no);
				$smarty->assign('page', 'admins');
				break;
			case 'domain_admins':
				break;
			case 'config':
				$smarty->assign('list', 'config');
				$smarty->assign('config', $param);
				$smarty->assign('page', 'configuration');
				break;
			case 'logs':
				$smarty->assign('list', 'logs');
				$smarty->assign('log', $param);
				$smarty->assign('log_no', $mail->log_no);
				$smarty->assign('page', 'logs');
				break;
		}
	}

	function smarty_file($file) {
		global $smarty, $mail;

		$this->show_message();
		switch ($mail->priv_level) {
			case $mail->priv_admin:
				$smarty->display('admin/'. $file);
				break;
			case $mail->priv_domain_admin:
				$smarty->display('domain_admin/'. $file);
				break;
			case $mail->priv_user:
				$smarty->display('user/'. $file);
				break;
		}
	}
}

?>
