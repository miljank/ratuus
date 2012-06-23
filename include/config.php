<?
#
#
session_start();

#
# Includes Ratuus class
require_once(WEB_FOLDER_PATH . "/include/libratuus.php");
$mail = new Ratuus;

require_once('db_config.php');

#
# DB Connection paremeters
$mail->db_host = $db_host;
$mail->db_user = $db_user;
$mail->db_pass = $db_pass;
$mail->db_name = $db_name;

#
# Include Smarty
require_once(WEB_FOLDER_PATH . "/include/smarty/Smarty.class.php");
$smarty = new Smarty;

#
# Smarty config
$smarty->template_dir = WEB_FOLDER_PATH . "/templates/ratuus/";
$smarty->compile_dir = WEB_FOLDER_PATH . "/sm_comp/templates_c";
$smarty->cache_dir = WEB_FOLDER_PATH . "/sm_comp/cache";
$smarty->config_dir = WEB_FOLDER_PATH . "/sm_comp/configs";

require_once(WEB_FOLDER_PATH . "/include/smarty_functions.php");
$ssmarty = new show_smarty;

#
require_once(WEB_FOLDER_PATH . "/include/func_exec.php");
$exec = new func_exec;

#
# Creates a connection to the DB
$mail->db_connect($mail->db_host, $mail->db_user, $mail->db_pass, $mail->db_name);

#
# Parses and checks users input
$mail->check_input();

#
# Fetch configuration from DB
$mail->get_config();

#
# Generate links
$address = $_SERVER['SCRIPT_NAME'];
$link['logout'] = $address . "?action=logout";
$link['list_users'] = $address . "?list=users&domain=";
$link['add_user'] = $address . "?form=addUser&domain=";
$link['list_aliases'] = $address . "?list=alias&domain=";
$link['add_alias'] = $address . "?form=addAlias&domain=";
$link['list_domains'] = $address . "?list=domains";
$link['add_domain'] = $address . "?form=addDomain";
$link['modify_domain'] = $address . "?form=changeDomain&domain=";
$link['delete_domain'] = $address . "?delete=domain&domain=";
$link['modify_alias'] = $address . "?form=changeAlias&domain=". $mail->domain ."&alias=";
$link['delete_alias'] = $address . "?delete=alias&domain=". $mail->domain ."&alias=";
$link['add_config'] = $address . "?form=addConfig";
$link['change_config'] = $address . "?form=changeConfig&config_opt=";
$link['delete_config'] = $address . "?delete=config&config_opt=";
$link['list_config'] = $address . "?list=config";
$link['list_logs'] = $address ."?list=logs";
$link['delete_log'] = $address . "?delete=log";
$link['modify_user'] = $address . "?form=changeUser&domain=". $mail->domain ."&username=";
$link['change_password'] = $address . "?form=changeUser&username=$mail->auth_user";
$link['delete_user'] = $address . "?delete=user&domain=". $mail->domain ."&username=";
$link['add_admin'] = $address . "?form=addAdmin";
$link['list_admins'] = $address . "?list=admins";
$link['modify_admin'] = $address . "?form=changeAdmin&username=";
$link['delete_admin'] = $address . "?delete=admin&username=";
$link['add_domain_admins'] = $address . "?form=addDomainAdmin";
$link['search'] = $address . "?list=search";
$link['main'] = $address ."?list=main";

#
#
$conf['aliases'] = 'Alias No:';
$conf['mailboxes'] = 'Mailbox No:';
$conf['transport'] = 'Transport:';
$conf['user_quota'] = 'User quota:';
$conf['domain_quota'] = 'Domain quota:';
$conf['limit'] = 'Page limit:';

#
# Assign vars to smarty
$smarty->assign('link', $link);
$smarty->assign('address', $address);
$smarty->assign('conf', $conf);
?>
