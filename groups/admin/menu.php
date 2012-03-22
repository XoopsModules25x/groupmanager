<?php

defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

global $xoopsModule, $xoopsUser;

$dirname = basename(dirname(dirname(__FILE__)));
$module_handler = xoops_gethandler('module');
$module = $module_handler->getByDirname($dirname);
$pathIcon32 = $module->getInfo('icons32');

xoops_loadLanguage('admin', $dirname);

$i = 0;

// Index
$adminmenu[$i]['title'] = _MI_GROUPS_ADMIN0;
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]["icon"] = $pathIcon32.'/home.png';
$i++;

$adminmenu[$i]['title'] = _MI_GROUPS_ADMIN1;
$adminmenu[$i]['link'] = "admin/main.php";
$adminmenu[$i]["icon"] = $pathIcon32.'/manage.png';

//-----------------------

//Check user permission to display permission page
//global $xoopsModule;
$xoopsModule = XoopsModule::getByDirname("groups");

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
$i++;
$adminmenu[$i]['title'] = _MI_GROUPS_ADMIN2;
$adminmenu[$i]['link'] = "admin/perm.php";
$adminmenu[$i]["icon"] = $pathIcon32.'/permissions.png';
}

$i++;
$adminmenu[$i]['title'] = _MI_GROUPS_ABOUT;
$adminmenu[$i]['link'] =  "admin/about.php";
$adminmenu[$i]["icon"] = $pathIcon32.'/about.png';