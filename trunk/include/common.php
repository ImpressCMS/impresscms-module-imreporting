<?php
/**
* Common file of the module included on all pages of the module
*
* @copyright	
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Rodrigo Lima aka TheRplima <therplima@impresscms.org>
* @package		imreporting
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

if(!defined("IMREPORTING_DIRNAME"))		define("IMREPORTING_DIRNAME", $modversion['dirname'] = basename(dirname(dirname(__FILE__))));
if(!defined("IMREPORTING_URL"))			define("IMREPORTING_URL", ICMS_URL.'/modules/'.IMREPORTING_DIRNAME.'/');
if(!defined("IMREPORTING_ROOT_PATH"))	define("IMREPORTING_ROOT_PATH", ICMS_ROOT_PATH.'/modules/'.IMREPORTING_DIRNAME.'/');
if(!defined("IMREPORTING_IMAGES_URL"))	define("IMREPORTING_IMAGES_URL", IMREPORTING_URL.'images/');
if(!defined("IMREPORTING_ADMIN_URL"))	define("IMREPORTING_ADMIN_URL", IMREPORTING_URL.'admin/');

// Include the common language file of the module
icms_loadLanguageFile('imreporting', 'common');

include_once(IMREPORTING_ROOT_PATH . "include/functions.php");

// Creating the module object to make it available throughout the module
$imreportingModule = icms_getModuleInfo(IMREPORTING_DIRNAME);
if (is_object($imreportingModule)){
	$imreporting_moduleName = $imreportingModule->getVar('name');
}

// Find if the user is admin of the module and make this info available throughout the module
$imreporting_isAdmin = icms_userIsAdmin(IMREPORTING_DIRNAME);

// Creating the module config array to make it available throughout the module
$imreportingConfig = icms_getModuleConfig(IMREPORTING_DIRNAME);

// creating the icmsPersistableRegistry to make it available throughout the module
global $icmsPersistableRegistry;
$icmsPersistableRegistry = IcmsPersistableRegistry::getInstance();

?>