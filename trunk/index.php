<?php
/**
* User index page of the module
*
* Including the article page
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

$xoopsOption['template_main'] = 'imreporting_index.html';
/** Include the ICMS header file */
include_once ICMS_ROOT_PATH . '/header.php';

// At which record shall we start display
$clean_start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$clean_article_uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;
$clean_year = isset($_GET['y']) ? intval($_GET['y']) : false;
$clean_month = isset($_GET['m']) ? intval($_GET['m']) : false;
$clean_cid = isset($_GET['cid']) ? intval($_GET['cid']) : false;
 $Basic_Check = $xoopsConfig['language'] == "persian" && $xoopsConfig['use_ext_date'] == 1;
if(!empty($_GET['y']) && !empty($_GET['m']) && $Basic_Check)
{
		$jyear = $clean_year;
		$jmonth = $clean_month;
		if ($jmonth <= '6'){
			$jday = '31';
		}elseif($jmonth > '6' && $jmonth <= '11'){
			$jday = '31';
		}else{
						$jday = '29';
		}
		list($gyear, $gmonth, $gday) = jalali_to_gregorian( $jyear, $jmonth, $jday );
		$clean_year =  $gyear;
		$clean_month = $gmonth;

}

$imreporting_article_handler = xoops_getModuleHandler('article');

$xoopsTpl->assign('imreporting_articles', $imreporting_article_handler->getArticles($clean_start, $xoopsModuleConfig['articles_limit'], $clean_article_uid, $clean_cid, $clean_year, $clean_month));
/**
 * Create Navbar
 */
include_once ICMS_ROOT_PATH . '/class/pagenav.php';
$articles_count = $imreporting_article_handler->getArticlesCount($clean_article_uid, $clean_cid, $clean_year, $clean_month);
$extr_argArray = array();
$category_pathArray = array();

if ($clean_article_uid) {
	$imreporting_poster_link = icms_getLinkedUnameFromId($clean_article_uid);
	$xoopsTpl->assign('imreporting_rss_url', IMREPORTING_URL . 'rss.php?uid=' . $clean_article_uid);
	$xoopsTpl->assign('imreporting_rss_info', _MD_IMREPORTING_RSS_POSTER);
	$extr_arg = 'uid=' . $clean_article_uid;
} else {
	$xoopsTpl->assign('imreporting_rss_url', IMREPORTING_URL . 'rss.php');
	$xoopsTpl->assign('imreporting_rss_info', _MD_IMREPORTING_RSS_GLOBAL);
	$extr_arg = '';
}
if ($clean_article_uid) {
	$extr_argArray[] = 'uid=' . $clean_article_uid;
	$category_pathArray[] = sprintf(_CO_IMREPORTING_ARTICLE_FROM_USER, icms_getLinkedUnameFromId($clean_article_uid));
}
if ($clean_cid) {
	$imtagging_category_handler = xoops_getModuleHandler('category', 'imtagging');
	$category_name = $imtagging_category_handler->getCategoryName($clean_cid);
	$category_pathArray[] = $category_name;
	$extr_argArray[] = 'cid=' . $clean_cid;
}
	$config_handler =& xoops_gethandler('config');
	$xoopsConfig =& $config_handler->getConfigsByCat(XOOPS_CONF);
if ($clean_year && $clean_month) {
if($Basic_Check)
{
		$gyear = $clean_year;
		$gmonth = $clean_month;
		$gday = 1;
		list($jyear, $jmonth, $jday) = gregorian_to_jalali( $gyear, $gmonth, $gday );
		$clean_year =  icms_conv_nr2local($jyear);
		$clean_month = $jmonth;

}
	$category_pathArray[] = sprintf(_CO_IMREPORTING_ARTICLE_FROM_MONTH, imreporting_getMonthNameById($clean_month), $clean_year);
}

$extr_arg = count($extr_argArray) > 0 ? implode('&amp;', $extr_argArray) : '';

$pagenav = new XoopsPageNav($articles_count, $xoopsModuleConfig['articles_limit'], $clean_start, 'start', $extr_arg);
$xoopsTpl->assign('navbar', $pagenav->renderNav());

$xoopsTpl->assign('imreporting_module_home', imreporting_getModuleName(true, true));

$category_path = count($category_pathArray) > 0 ? implode(' > ', $category_pathArray) : false;
$xoopsTpl->assign('imreporting_category_path', $category_path);

$xoopsTpl->assign('articles_showbreadcrumb', $xoopsModuleConfig['articles_showbreadcrumb']);
$xoopsTpl->assign('change_date_format', (isset($xoopsModuleConfig['date_format']) && $xoopsModuleConfig['date_format'] != '')?true:false);
$xoopsTpl->assign('imreporting_showSubmitLink', true);

/** Include the module's footer */
include_once 'footer.php';
?>
