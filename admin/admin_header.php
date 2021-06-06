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
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 */

use Xmf\Module\Admin;
use XoopsModules\Groupmanager\Helper;

require dirname(__DIR__) . '/preloads/autoloader.php';

require dirname(__DIR__, 3) . '/include/cp_header.php';
//require $GLOBALS['xoops']->path('www/class/xoopsformloader.php');
require dirname(__DIR__) . '/include/common.php';

$moduleDirName = \basename(\dirname(__DIR__));

/** @var \XoopsModules\Groupmanager\Helper $helper */
$helper = Helper::getInstance();

/** @var \Xmf\Module\Admin $adminObject */
$adminObject = Admin::getInstance();

//$myts = \MyTextSanitizer::getInstance();

//if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof \XoopsTpl)) {
//    require $GLOBALS['xoops']->path('class/template.php');
//    $xoopsTpl = new XoopsTpl();
//}

//$pathIcon16      = Xmf\Module\Admin::iconUrl('', 16);
//$pathIcon32      = Xmf\Module\Admin::iconUrl('', 32);
//$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

// Local icons path
//$xoopsTpl->assign('pathIcon16', $pathIcon16);
//$xoopsTpl->assign('pathIcon32', $pathIcon32);

// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('common');

//Module specific elements
//require $GLOBALS['xoops']->path("modules/{$moduleDirName}/include/functions.php");
//require $GLOBALS['xoops']->path("modules/{$moduleDirName}/include/config.php");

//xoops_cp_header();
