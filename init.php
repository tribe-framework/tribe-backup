<?php
namespace Wildfire\Core;

//max script execution time 10 mins
set_time_limit(600);

$sql = new MySQL();
$dash = new Dash();
$admin = new Admin();

$type = 'mysql_backup';
$types = $dash->getTypes();
$menus = $dash->getMenus();
$session_user = $dash->getSessionUser();

if ($types['user']['roles'][$session_user['role_slug']]['role'] != 'admin') {
    die('You do not have permission for this action');
}
