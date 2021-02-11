<?php
namespace Wildfire\Core;

$sql = new MySQL();
$dash = new Dash();
$admin = new Admin();
$theme = new Theme();

$type = 'syslog_backup';
$types = $dash->getTypes();
$menus = $dash->getMenus();
$session_user = $dash->getSessionUser();

if ($types['user']['roles'][$session_user['role_slug']]['role'] != 'admin') {
	die('You do not have permission for this action');
}
