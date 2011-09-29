<?php
/**
* Articles by month block file
*
* This file holds the functions needed for the articles by month block
*
* @copyright	
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Rodrigo Lima aka TheRplima <therplima@impresscms.org>
* @package		imreporting
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

function imreporting_article_by_category_show($options)
{
	include_once(ICMS_ROOT_PATH . '/modules/imreporting/include/common.php');
	$imreporting_article_handler = xoops_getModuleHandler('article', 'imreporting');
	$block['articles_by_categories'] = $imreporting_article_handler->getArticlesCountByCategories();

	return $block;
}

?>