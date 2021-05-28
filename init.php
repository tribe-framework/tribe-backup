<?php
namespace Wildfire;

$dash = new Core\Dash();
$admin = new Core\Admin();
$sql = new Core\MySQL();
$auth = new Auth\Auth();

$types = $dash->getTypes();
$menus = $dash->getMenus();
$currentUser = $auth->getCurrentUser();

//max script execution time 10 mins
set_time_limit(600);

$type = 'mysql_backup';

if ($types['user']['roles'][$currentUser['role_slug']]['role'] != 'admin') {
    die('You do not have permission for this action');
}
