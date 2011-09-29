<?php
/**
* Footer page included at the end of each page on user side of the mdoule
*
* @copyright	
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Rodrigo Lima aka TheRplima <therplima@impresscms.org>
* @package		imreporting
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

$xoopsTpl->assign("imreporting_adminpage", imreporting_getModuleAdminLink());
$xoopsTpl->assign("imreporting_is_admin", $imreporting_isAdmin);
$xoopsTpl->assign('imreporting_url', IMREPORTING_URL);
$xoopsTpl->assign('imreporting_images_url', IMREPORTING_IMAGES_URL);

$xoTheme->addStylesheet(IMREPORTING_URL . 'module'.(( defined("_ADM_USE_RTL") && _ADM_USE_RTL )?'_rtl':'').'.css');

include_once(ICMS_ROOT_PATH . '/footer.php');

?>