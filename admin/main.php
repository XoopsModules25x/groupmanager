<?php

/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       Kaotik, GigaPHP, XOOPS Development Team
 */
require __DIR__ . '/admin_header.php';

require_once XOOPS_ROOT_PATH . '/class/template.php';
if (!isset($xoopsTpl)) {
    $xoopsTpl = new XoopsTpl();
}
$xoopsTpl->caching = 0;
$xoopsTpl->assign('xoops_dirname', $xoopsModule->getVar('dirname'));


// CHECK IF SUBMIT WAS PRESSED
/** @var \XoopsMemberHandler $memberHandler */
if (isset($_POST['add_x']) || isset($_POST['del_x'])) {
    if (isset($_POST['add_x'])) {
        $memberHandler    = xoops_getHandler('member');
        $membership = $memberHandler->addUserToGroup($_POST['groupid'], $_POST['all']);
    }

    if (isset($_POST['del_x'])) {
        $memberHandler    = xoops_getHandler('member');
        $membership = $memberHandler->removeUsersFromGroup($_POST['groupid'], [$_POST['curr']]);
    }
}

/*
CREATE USER LIST
*/

xoops_cp_header();
$adminObject->displayNavigation(basename(__FILE__));

/*
//Check user permission to display permission page
$perm_name = 'allowedgrp';
$perm_itemid = 1;
if ($xoopsUser) {
        $groups = $xoopsUser->getGroups();
} else {
        $groups = XOOPS_GROUP_ANONYMOUS;
}
$module_id = $xoopsModule->getVar('mid');
$gpermHandler = xoops_getHandler('groupperm');
if ($gpermHandler->checkRight($perm_name, $perm_itemid, $groups, $module_id)) {
        // allowed, so display contents within the category
        $xoopsTpl->assign('perallow', 1);
} else {
        // not allowed, display an error message or redirect to another page
        $xoopsTpl->assign('perallow', 0);
}
//---------------------------------------
*/

$grpInfo = [];

$memberHandler = xoops_getHandler('member');
$groups        = $memberHandler->getGroups();

$count = count($groups);
for ($i = 0; $i < $count; ++$i) {
    $id   = $groups[$i]->getVar('groupid');
    $name = $groups[$i]->getVar('name');
    //Skip anonymous group
    if (3 == $id) {
        continue;
    }

    //check if user has permission to change this group
    $perm_name   = 'groupper';
    $perm_itemid = $id;
    $groups2     = XOOPS_GROUP_ANONYMOUS;
    if ($xoopsUser) {
        $groups2 = $xoopsUser->getGroups();
    }
    $module_id    = $xoopsModule->getVar('mid');
    $gpermHandler = xoops_getHandler('groupperm');
    if ($gpermHandler->checkRight($perm_name, $perm_itemid, $groups2, $module_id)) {
    } else {
        continue;
    }
    //----------------*/

    $uids     = $memberHandler->getUsersByGroup($id);
    $criteria = new Criteria('uid', '(' . implode(',', $uids) . ')', 'IN');
    $criteria->setSort('uname');
    $users = $memberHandler->getUserList($criteria);

    $grpInfo[$i]['users'] = $users;
    $grpInfo[$i]['name']  = $name;
    $grpInfo[$i]['id']    = $id;
}

/*---------------------------//
Get all users
//----------------------------------*/
$allUsr        = [];
$memberHandler = xoops_getHandler('member');
$foundusers    = $memberHandler->getUsers();
foreach (array_keys($foundusers) as $j) {
    //echo $foundusers[$j]->getVar('uname').'<br>';
    $uid          = $foundusers[$j]->getVar('uid');
    $username     = $foundusers[$j]->getVar('uname');
    $allUsr[$uid] = $username;
}
//--------------------------//

$xoopsTpl->assign('allUsr', $allUsr);
$xoopsTpl->assign('grpInfo', $grpInfo);
$xoopsTpl->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);

$xoopsTpl->display('db:gm_main.tpl');

require_once __DIR__ . '/admin_footer.php';
//xoops_cp_footer();
