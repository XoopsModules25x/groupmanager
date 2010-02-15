<?php
require_once '../../../include/cp_header.php';

require_once XOOPS_ROOT_PATH . '/class/template.php';
if (!isset($xoopsTpl)) {$xoopsTpl = new XoopsTpl();}
$xoopsTpl->xoops_setCaching(0);
$xoopsTpl->assign('xoops_dirname', $xoopsModule->getVar('dirname'));

xoops_cp_header();

   include_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';
    $module_id = $xoopsModule->getVar('mid');


	//$item_list = array('1' => 'Category 1', '2' => 'Category 2', '3' => 'Category 3');
	$item_list=array();
	$member_handler =& xoops_gethandler('member');
	$groups =& $member_handler->getGroups();
	foreach ($groups as $grp){
	$item_list[$grp->getVar('groupid')]=$grp->getVar('name');
	}
	
    //--------------------------------------------------------------//
    //Added constants for use in translation - by GibaPhp           //
    //--------------------------------------------------------------//

	$title_of_form = _AM_GROUPS_PERMISS_GRP_TITLE;
	$perm_name = 'groupper';
	$perm_desc = _AM_GROUPS_PERMISS_GRP_DESC;

    $form = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc);
    foreach ($item_list as $item_id => $item_name) {
    $form->addItem($item_id, $item_name);
    }
    echo $form->render();
	
	echo "<br /><br />";
	
	$item_list = array('1' => _AM_GROUPS_ITEM_LIST);
	$title_of_form = _AM_GROUPS_TITLE_OF_FORM;
	$perm_desc = _AM_GROUPS_PERM_DESC;
	$perm_name = 'allowedgrp';
    $form = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc);
    foreach ($item_list as $item_id => $item_name) {
    $form->addItem($item_id, $item_name);
    }
    echo $form->render();
	
xoops_cp_footer();

?>