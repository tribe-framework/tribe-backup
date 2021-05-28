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
$currentUser = $auth->getCurrentUser();

if ($types['user']['roles'][$currentUser['role_slug']]['role'] != 'admin') {
    die('You do not have permission for this action');
}
