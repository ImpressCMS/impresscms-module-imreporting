<?php
/**
* imReporting version infomation
*
* This file holds the configuration information of this module
*
* @copyright	
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Rodrigo Lima aka TheRplima <therplima@impresscms.org>
* @package		imreporting
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

/**  General Information  */
$modversion = array(
  'name'=> _MI_IMREPORTING_MD_NAME,
  'version'=> 1.0,
  'description'=> _MI_IMREPORTING_MD_DESC,
  'author'=> "Rodrigo Lima aka TheRplima",
  'credits'=> "",
  'help'=> "",
  'license'=> "GNU General Public License (GPL)",
  'official'=> 0,
  'dirname'=> basename( dirname( __FILE__ ) ),

/**  Images information  */
  'iconsmall'=> "images/icon_small.png",
  'iconbig'=> "images/icon_big.png",
  'image'=> "images/icon_big.png", /* for backward compatibility */

/**  Development information */
  'status_version'=> "1.0",
  'status'=> "Beta",
  'date'=> "Unreleased",
  'author_word'=> "",

/** Contributors */
  'developer_website_url' => "http://www.rodrigoplima.com",
  'developer_website_name' => "Rodrigo Lima",
  'developer_email' => "therplima@impresscms.org");

$modversion['people']['developers'][] = "[url=http://community.impresscms.org/userinfo.php?uid=106]Rodrigo Lima aka TheRplima[/url]";
//$modversion['people']['testers'][] = "";
//$modversion['people']['translators'][] = "";
//$modversion['people']['documenters'][] = "";
//$modversion['people']['other'][] = "";

/** Manual */
$modversion['manual']['wiki'][] = "<a href='http://wiki.impresscms.org/index.php?title=imReporting' target='_blank'>English</a>";

$modversion['warning'] = _CO_ICMS_WARNING_BETA;

/** Administrative information */
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

/** Database information */
$modversion['object_items'][1] = 'article';

$modversion["tables"] = icms_getTablesArray($modversion['dirname'], $modversion['object_items']);

/** Install and update informations */
$modversion['onInstall'] = "include/onupdate.inc.php";
$modversion['onUpdate'] = "include/onupdate.inc.php";

/** Search information */
$modversion['hasSearch'] = 1;
$modversion['search'] = array (
  'file' => "include/search.inc.php",
  'func' => "imreporting_search");

/** Menu information */
$modversion['hasMain'] = 1;
global $xoopsModule;
if (is_object($xoopsModule) && $xoopsModule->dirname() == 'imreporting') {
	$imreporting_article_handler = xoops_getModuleHandler('article', 'imreporting');
	if ($imreporting_article_handler->userCanSubmit()) {
		$modversion['sub'][1]['name'] = _MI_IMREPORTING_ARTICLE_ADD;
		$modversion['sub'][1]['url'] = 'article.php?op=mod';
	}
}

/** Blocks information */
$modversion['blocks'][1] = array(
  'file' => 'article_recent.php',
  'name' => _MI_IMREPORTING_ARTICLERECENT,
  'description' => _MI_IMREPORTING_ARTICLERECENTDSC,
  'show_func' => 'imreporting_article_recent_show',
  'edit_func' => 'imreporting_article_recent_edit',
  'options' => 'pubdate|10|50|0|0|0||0|0',
  'template' => 'imreporting_article_recent.html');

$modversion['blocks'][] = array(
  'file' => 'article_by_month.php',
  'name' => _MI_IMREPORTING_ARTICLEBYMONTH,
  'description' => _MI_IMREPORTING_ARTICLEBYMONTHDSC,
  'show_func' => 'imreporting_article_by_month_show',
  'template' => 'imreporting_article_by_month.html');

$modversion['blocks'][] = array(
  'file' => 'article_by_category.php',
  'name' => _MI_IMREPORTING_ARTICLEBYCATEGORY,
  'description' => _MI_IMREPORTING_ARTICLEBYCATEGORYDSC,
  'show_func' => 'imreporting_article_by_category_show',
  'template' => 'imreporting_article_by_category.html');


/** Templates information */
$modversion['templates'][1] = array(
  'file' => 'imreporting_header.html',
  'description' => 'Module Header');

$modversion['templates'][] = array(
  'file' => 'imreporting_footer.html',
  'description' => 'Module Footer');

$modversion['templates'][]= array(
  'file' => 'imreporting_admin_article.html',
  'description' => 'article Admin Index');

$modversion['templates'][]= array(
  'file' => 'imreporting_article.html',
  'description' => 'article Index');

$modversion['templates'][]= array(
  'file' => 'imreporting_single_article.html',
  'description' => 'single article');

$modversion['templates'][]= array(
  'file' => 'imreporting_index.html',
  'description' => 'article Index');

/** Preferences information */
// Retrieve the group user list, because the automatic group_multi config formtype does not include Anonymous group :-(
$member_handler =& xoops_getHandler('member');
$groups_array = $member_handler->getGroupList();
foreach($groups_array as $k=>$v) {
	$select_groups_options[$v] = $k;
}
$modversion['config'][1] = array(
  'name' => 'poster_groups',
  'title' => '_MI_IMREPORTING_POSTERGR',
  'description' => '_MI_IMREPORTING_POSTERGRDSC',
  'formtype' => 'select_multi',
  'valuetype' => 'array',
  'options' => $select_groups_options,
  'default' =>  '1');

$modversion['config'][] = array(
  'name' => 'articles_limit',
  'title' => '_MI_IMREPORTING_LIMIT',
  'description' => '_MI_IMREPORTING_LIMITDSC',
  'formtype' => 'textbox',
  'valuetype' => 'text',
  'default' => 5);

$modversion['config'][] = array(
  'name' => 'articles_autopublish',
  'title' => '_MI_IMREPORTING_AUTOPUBLISH',
  'description' => '_MI_IMREPORTING_AUTOPUBLISHDSC',
  'formtype' => 'yesno',
  'valuetype' => 'int',
  'default' => 0);

$modversion['config'][] = array(
  'name' => 'articles_showbreadcrumb',
  'title' => '_MI_IMREPORTING_SHOWBREADCRUMB',
  'description' => '_MI_IMREPORTING_SHOWBREADCRUMBDSC',
  'formtype' => 'yesno',
  'valuetype' => 'int',
  'default' => 1);

$modversion['config'][] = array(
  'name' => 'date_format',
  'title' => '_MI_IMREPORTING_DATEFORMAT',
  'description' => '_MI_IMREPORTING_DATEFORMATDSC',
  'formtype' => 'textbox',
  'valuetype' => 'text',
  'default' => '');

/** Comments information */
$modversion['hasComments'] = 1;

$modversion['comments'] = array(
  'itemName' => 'article_id',
  'pageName' => 'article.php',
  /* Comment callback functions */
  'callbackFile' => 'include/comment.inc.php',
  'callback' => array(
    'approve' => 'imreporting_com_approve',
    'update' => 'imreporting_com_update')
    );

/** Notification information */

$modversion['hasNotification'] = 1;

$modversion['notification'] = array (
  'lookup_file' => 'include/notification.inc.php',
  'lookup_func' => 'imreporting_notify_iteminfo');

$modversion['notification']['category'][1] = array (
  'name' => 'global',
  'title' => _MI_IMREPORTING_GLOBAL_NOTIFY,
  'description' => _MI_IMREPORTING_GLOBAL_NOTIFY_DSC,
  'subscribe_from' => array('index.php', 'article.php'));

$modversion['notification']['event'][1] = array(
  'name' => 'article_published',
  'category'=> 'global',
  'title'=> _MI_IMREPORTING_GLOBAL_ARTICLE_PUBLISHED_NOTIFY,
  'caption'=> _MI_IMREPORTING_GLOBAL_ARTICLE_PUBLISHED_NOTIFY_CAP,
  'description'=> _MI_IMREPORTING_GLOBAL_ARTICLE_PUBLISHED_NOTIFY_DSC,
  'mail_template'=> 'global_article_published',
  'mail_subject'=> _MI_IMREPORTING_GLOBAL_ARTICLE_PUBLISHED_NOTIFY_SBJ);

?>