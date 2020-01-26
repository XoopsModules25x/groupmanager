<?php

//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <https://xoops.org>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
//

require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
require_once __DIR__ . '/admin_header.php';

require_once XOOPS_ROOT_PATH . '/class/template.php';
if (!isset($xoopsTpl)) {
    $xoopsTpl = new XoopsTpl();
}
//$xoopsTpl->caching=0;
$xoopsTpl->caching = 0;
$xoopsTpl->assign('xoops_dirname', $GLOBALS['xoopsModule']->getVar('dirname'));

// CHECK IF SUBMIT WAS PRESSED
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

/*

//Check user permission to display permission page
$perm_name = 'allowedgrp';
$perm_itemid = 1;
if ($xoopsUser) {
        $groups = $xoopsUser->getGroups();
} else {
        $groups = XOOPS_GROUP_ANONYMOUS;
}
$module_id = $GLOBALS['xoopsModule']->getVar('mid');
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
    $module_id    = $GLOBALS['xoopsModule']->getVar('mid');
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

$xoopsTpl->display('db:gm_main.tpl');

require_once __DIR__ . '/admin_footer.php';
//xoops_cp_footer();
