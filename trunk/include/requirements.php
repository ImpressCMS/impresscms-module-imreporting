<?php
/**
* Check requirements of the module
*
* @copyright	
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Rodrigo Lima aka TheRplima <therplima@impresscms.org>
* @package		imreporting
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

$failed_requirements = array();

/* ImpressCMS Builtd needs to be at lest 24 */
if (ICMS_VERSION_BUILD < 24) {
	$failed_requirements[] = _AM_IMREPORTING_REQUIREMENTS_ICMS_BUILD;
}

/* imReporting needs imTagging */
$imtaggingModule = icms_getModuleInfo('imtagging');
if (!$imtaggingModule) {
	$failed_requirements[] = _AM_IMREPORTING_REQUIREMENTS_IMTAGGING;
}

/* imReporting needs jQuery library and preload */
$jquery_library_exists = file_exists(ICMS_LIBRARIES_PATH.'/jquery/jquery.js');
if (!$jquery_library_exists) {
	$failed_requirements[] = _AM_IMREPORTING_REQUIREMENTS_JQUERY_LIB;
}
$jquery_preload_exists = file_exists(ICMS_PRELOAD_PATH.'/jquery.php');
$jquery_preload_exists_imtagging = (is_object($imtaggingModule))?file_exists(ICMS_ROOT_PATH.'/modules/'.$imtaggingModule->getVar('dirname').'/preload/jquery.php'):false;
if (!$jquery_preload_exists && !$jquery_preload_exists_imtagging) {
	$failed_requirements[] = _AM_IMREPORTING_REQUIREMENTS_JQUERY_PRE;
}

if (count($failed_requirements) > 0) {
	xoops_cp_header();
	$icmsAdminTpl->assign('failed_requirements', $failed_requirements);
	$icmsAdminTpl->display(IMREPORTING_ROOT_PATH . 'templates/imreporting_requirements.html');
	xoops_cp_footer();
	exit;
}
?>