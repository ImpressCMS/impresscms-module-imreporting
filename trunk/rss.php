<?php
/**
* Generating an RSS feed
*
* @copyright	
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Rodrigo Lima aka TheRplima <therplima@impresscms.org>
* @package		imreporting
* @version		$Id$
*/
/** Include the module's header for all pages */
include_once 'header.php';
include_once ICMS_ROOT_PATH.'/header.php';

$clean_article_uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;

include_once IMREPORTING_ROOT_PATH.'/class/icmsfeed.php';
$imreporting_feed = new IcmsFeed();

$imreporting_feed->title = $xoopsConfig['sitename'] . ' - ' . $xoopsModule->name();
$imreporting_feed->url = XOOPS_URL;
$imreporting_feed->description = $xoopsConfig['slogan'];
$imreporting_feed->language = _LANGCODE;
$imreporting_feed->charset = _CHARSET;
$imreporting_feed->category = $xoopsModule->name();

$imreporting_article_handler = xoops_getModuleHandler('article');
//ImbloggingPostHandler::getPosts($start = 0, $limit = 0, $article_uid = false, $year = false, $month = false
$articlesArray = $imreporting_article_handler->getArticles(0, 10, $clean_article_uid);

foreach($articlesArray as $articleArray) {
	$imreporting_feed->feeds[] = array (
	  'title' => $articleArray['article_title'],
	  'link' => str_replace('&', '&amp;', $articleArray['itemUrl']),
	  'description' => htmlspecialchars(str_replace('&', '&amp;', $articleArray['article_summary']), ENT_QUOTES),
	  'pubdate' => $articleArray['article_published_date_int'],
	  'guid' => str_replace('&', '&amp;', $articleArray['itemUrl']),
	);
}

$imreporting_feed->render();
?>