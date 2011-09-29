<?php
/**
* English language constants related to module information
*
* @copyright	
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Rodrigo Lima aka TheRplima <therplima@impresscms.org>
* @package		imreporting
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// Module Info
// The name of this module

global $xoopsModule;
define("_MI_IMREPORTING_MD_NAME", "imReporting");
define("_MI_IMREPORTING_MD_DESC", "ImpressCMS Simple News and Article module");

define("_MI_IMREPORTING_ARTICLES", "Articles");

// Configs
define("_MI_IMREPORTING_POSTERGR", "Groups allowed to post articles");
define("_MI_IMREPORTING_POSTERGRDSC", "Select the groups which are allowed to create new articles. Please note that a user belonging to one of these groups will be able to post articles directly on the site if the option of auto-publish articles bellow is marked as YES");
define("_MI_IMREPORTING_LIMIT", "Articles limit");
define("_MI_IMREPORTING_LIMITDSC", "Number of articles to display on user side.");
define("_MI_IMREPORTING_AUTOPUBLISH", "Auto Publish Articles?");
define("_MI_IMREPORTING_AUTOPUBLISHDSC", "Set to <b>NO</b> to moderate the articles posted by the users. If set to <b>YES</b> all articles posted by the users will be published without admin intervention.");
define("_MI_IMREPORTING_SHOWBREADCRUMB", "Show breadcrumb?");
define("_MI_IMREPORTING_SHOWBREADCRUMBDSC", "Select <b>YES</b> to show the navigation menu at the top of each page of the module.");
define("_MI_IMREPORTING_DATEFORMAT", "Date's format");
define("_MI_IMREPORTING_DATEFORMATDSC", "Please refer to the Php documentation (<a href='http://php.net/manual/en/function.date.php' target='_blank'>http://php.net/manual/en/function.date.php</a>) for more information on how to select the format. Note, if you don't type anything then the default date's format will be used.");

// Blocks
define("_MI_IMREPORTING_ARTICLERECENT", "Recent articles");
define("_MI_IMREPORTING_ARTICLERECENTDSC", "Display most recent articles");
define("_MI_IMREPORTING_ARTICLEBYMONTH", "Articles by month");
define("_MI_IMREPORTING_ARTICLEBYMONTHDSC", "Display list of months in which there were articles");
define("_MI_IMREPORTING_ARTICLEBYCATEGORY", "Articles by category");
define("_MI_IMREPORTING_ARTICLEBYCATEGORYDSC", "Display list of categories in which there were articles");

// Notifications
define("_MI_IMREPORTING_GLOBAL_NOTIFY", "All articles");
define("_MI_IMREPORTING_GLOBAL_NOTIFY_DSC", "Notifications related to all articles in the module");
define("_MI_IMREPORTING_GLOBAL_ARTICLE_PUBLISHED_NOTIFY", "New article published");
define("_MI_IMREPORTING_GLOBAL_ARTICLE_PUBLISHED_NOTIFY_CAP", "Notify me when a new article is published");
define("_MI_IMREPORTING_GLOBAL_ARTICLE_PUBLISHED_NOTIFY_DSC", "Receive notification when any new article is published.");
define("_MI_IMREPORTING_GLOBAL_ARTICLE_PUBLISHED_NOTIFY_SBJ", "[{X_SITENAME}] {X_MODULE} auto-notify : New article published");

// Submit button
define("_MI_IMREPORTING_ARTICLE_ADD", "Add a new article");
?>