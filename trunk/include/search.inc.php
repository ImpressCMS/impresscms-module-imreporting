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

function imreporting_search($queryarray, $andor, $limit, $offset, $userid)
{
	$imreporting_article_handler = xoops_getModuleHandler('article', 'imreporting');
	$articlesArray = $imreporting_article_handler->getArticlesForSearch($queryarray, $andor, $limit, $offset, $userid);

	$ret = array();

	foreach ($articlesArray as $articleArray) {
		$item['image'] = "images/article.png";
		$item['link'] = str_replace(IMREPORTING_URL, '', $articleArray['itemUrl']);
		$item['title'] = $articleArray['article_title'];
		$item['time'] = strtotime($articleArray['article_published_date']);
		$item['uid'] = $articleArray['article_posterid'];
		$ret[] = $item;
		unset($item);
	}
	return $ret;
}

?>