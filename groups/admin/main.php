<?php
// $Id: admin.php 8094 2011-11-06 09:52:56Z beckmi $ //
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
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

require_once '../../../include/cp_header.php';
include_once('admin_header.php');


require_once XOOPS_ROOT_PATH . '/class/template.php';
if (!isset($xoopsTpl)) {$xoopsTpl = new XoopsTpl();}
//$xoopsTpl->xoops_setCaching(0);
$xoopsTpl->caching=0;
$xoopsTpl->assign('xoops_dirname', $xoopsModule->getVar('dirname'));


// CHECK IF SUBMIT WAS PRESSED
if (isset($_POST['add_x']) or isset($_POST['del_x'])){

if (isset($_POST['add_x'])){
$hMember =& xoops_gethandler('member');
$membership =& $hMember->addUserToGroup($_POST['groupid'],$_POST['all']);
}

if (isset($_POST['del_x'])){
$hMember =& xoops_gethandler('member');
$membership =& $hMember->removeUsersFromGroup($_POST['groupid'],array($_POST['curr']));
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
$module_id = $xoopsModule->getVar('mid');
$gperm_handler =& xoops_gethandler('groupperm');
if ($gperm_handler->checkRight($perm_name, $perm_itemid, $groups, $module_id)) {
        // allowed, so display contents within the category
		$xoopsTpl->assign('perallow', 1);
} else {
        // not allowed, display an error message or redirect to another page
		$xoopsTpl->assign('perallow', 0);
}
//---------------------------------------
*/

$grpInfo=array();

$member_handler =& xoops_gethandler('member');
$groups =& $member_handler->getGroups();

$count = count($groups);
for ($i = 0; $i < $count; $i++) {

$id = $groups[$i]->getVar('groupid');
$name=$groups[$i]->getVar('name');
//Skip anonymous group
if ($id==3) continue;


//check if user has permission to change this group
$perm_name = 'groupper';
$perm_itemid = $id;
if ($xoopsUser) {
        $groups2 = $xoopsUser->getGroups();
} else {
        $groups2 = XOOPS_GROUP_ANONYMOUS;
}
$module_id = $xoopsModule->getVar('mid');
$gperm_handler =& xoops_gethandler('groupperm');
if ($gperm_handler->checkRight($perm_name, $perm_itemid, $groups2, $module_id)) {
} else {
        continue;
}
//----------------*/


$uids =& $member_handler->getUsersByGroup($id);
$criteria = new Criteria('uid', "(".implode(',', $uids).")", "IN");
$criteria->setSort('uname');
$users=$member_handler->getUserList($criteria);

$grpInfo[$i]['users']=$users;
$grpInfo[$i]['name']=$name;
$grpInfo[$i]['id']=$id;
}

/*---------------------------//
Get all users
//----------------------------------*/
$allUsr=array();
$member_handler =& xoops_gethandler('member');
$foundusers =& $member_handler->getUsers();
foreach (array_keys($foundusers) as $j) {
//echo $foundusers[$j]->getVar("uname").'<br>';
$uid=$foundusers[$j]->getVar("uid");
$username=$foundusers[$j]->getVar("uname");
$allUsr[$uid]=$username;
}
//--------------------------//


$xoopsTpl->assign('allUsr', $allUsr);
$xoopsTpl->assign('grpInfo', $grpInfo);

$xoopsTpl->display('db:gm_main.html');

include_once("admin_footer.php");
//xoops_cp_footer();