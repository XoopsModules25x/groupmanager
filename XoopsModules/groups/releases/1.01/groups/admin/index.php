<?php
require_once '../../../include/cp_header.php';


require_once XOOPS_ROOT_PATH . '/class/template.php';
if (!isset($xoopsTpl)) {$xoopsTpl = new XoopsTpl();}
$xoopsTpl->xoops_setCaching(0);
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
//---------------------------------------*/


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
xoops_cp_footer();
?>