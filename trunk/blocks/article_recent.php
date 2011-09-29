<?php
/**
 * Recent articles block file
 *
 * This file holds the functions needed for the recent articles block
 *
 * @copyright	
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Rodrigo Lima aka TheRplima <therplima@impresscms.org>
 * @package		imreporting
 * @version		$Id$
 */

if (! defined ( "ICMS_ROOT_PATH" ))
	die ( "ICMS root path not defined" );

function imreporting_article_recent_show($options) {
	include_once (ICMS_ROOT_PATH . '/modules/imreporting/include/common.php');
	$imreporting_article_handler = xoops_getModuleHandler ( 'article', 'imreporting' );

	$block ['orderby'] = $options [0];
	$block ['title_length'] = $options [2];
	$block ['show_summary'] = $options [3];
	$block ['act_spotlight'] = $options [4];
	
	if ($options [5] == 0 && $options [4] == 1){ //Show the lastest article
		$articles = $imreporting_article_handler->getArticles ();
		$block ['spotlight_article'] = reset($articles);
	}elseif ($options [5] != 0 && $options [4] == 1){
		$spot_article = $imreporting_article_handler->get($options [5]);
		$block ['spotlight_article'] = $spot_article->toArray();
	}else{
		$block ['spotlight_article'] = false;
	}

	$block ['spotlight_image'] = $options [6];
	
	$block ['imreporting_url'] = IMREPORTING_URL;
	
	$size = count ( $options );
	$values = array ( );
	for($i = 8; $i < $size; $i ++) {
		$values [] = $options [$i];
	}
	
	$order = ($options [0] == 'pubdate')?'article_published_date':(($options [0] == 'hits')?'counter':false);
	
	$block ['articles'] = $imreporting_article_handler->getArticles ( 0, $options [1], $options [7], $values, false, false, false, $order );
	
	return $block;
}

function imreporting_article_recent_edit($options) {
	include_once (ICMS_ROOT_PATH . '/modules/imreporting/include/common.php');
	include_once (ICMS_ROOT_PATH . '/class/xoopsform/formimage.php');
	$imreporting_article_handler = xoops_getModuleHandler ( 'article', 'imreporting' );
	$size = count ( $options );
	
	$form = '<table>';
	$form .= '<tr><td colspan="2" align="center"><h3 style="margin-bottom:0;">' . _MB_IMREPORTING_ARTICLE_GENERAL_CONFIGS . '</h3></td></tr>';
	
	/**
	 * Order by
	 */
	$orderby = new XoopsFormSelect ( '', 'options[0]', $options [0], 1 );
	$orderby->addOption ( 'pubdate', _MB_IMREPORTING_ARTICLE_ORDERBY_PUBDATE );
	$orderby->addOption ( 'hits', _MB_IMREPORTING_ARTICLE_ORDERBY_HITS );
	$form .= '<tr>';
	$form .= '<td>' . _MB_IMREPORTING_ARTICLE_ORDERBY . '</td>';
	$form .= '<td>' . $orderby->render () . '</td>';
	$form .= '</tr>';
	
	/**
	 * Articles Limit
	 */
	$limit = new XoopsFormText ( '', 'options[1]', 10, 255, $options [1] );
	$form .= '<tr>';
	$form .= '<td>' . _MB_IMREPORTING_ARTICLE_RECENT_LIMIT . '</td>';
	$form .= '<td>' . $limit->render () . '</td>';
	$form .= '</tr>';
	
	/**
	 * Titles Length
	 */
	$length = new XoopsFormText ( '', 'options[2]', 10, 255, $options [2] );
	$form .= '<tr>';
	$form .= '<td>' . _MB_IMREPORTING_ARTICLE_RECENT_TITLELENGTH . '</td>';
	$form .= '<td>' . $length->render () . '</td>';
	$form .= '</tr>';
	
	/**
	 * Show Summary
	 */
	$summ = new XoopsFormRadioYN ( '', 'options[3]', $options [3] );
	$form .= '<tr>';
	$form .= '<td>' . _MB_IMREPORTING_ARTICLE_SHOWSUMMARY . '</td>';
	$form .= '<td>' . $summ->render () . '</td>';
	$form .= '</tr>';
	
	$form .= '<tr><td colspan="2" align="center"><h3 style="margin-bottom:0;">' . _MB_IMREPORTING_ARTICLE_SPTOLIGHT_CONFIGS . '</h3></td></tr>';
	/**
	 * Activate Spotlight
	 */
	$actspot = new XoopsFormRadioYN ( '', 'options[4]', $options [4] );
	$form .= '<tr>';
	$form .= '<td>' . _MB_IMREPORTING_ARTICLE_ACTSPOTLIGHT . '</td>';
	$form .= '<td>' . $actspot->render () . '</td>';
	$form .= '</tr>';
	
	/**
	 * Spotlight Article
	 */
	$articles = $imreporting_article_handler->getArticles ();
	$pubarts = array ( );
	foreach ( $articles as $article ) {
		$pubarts [$article ['article_id']] = $article ['article_title'];
	}
	$spotarticle = new XoopsFormSelect ( '', 'options[5]', $options [5], 1 );
	$spotarticle->addOption ( 0, _MB_IMREPORTING_ARTICLE_LASTESTARTICLE );
	$spotarticle->addOptionArray ( $pubarts );
	$form .= '<tr>';
	$form .= '<td>' . _MB_IMREPORTING_ARTICLE_SPOTLIGHT_ARTICLE . '</td>';
	$form .= '<td>' . $spotarticle->render () . '</td>';
	$form .= '</tr>';
	
	/**
	 * Spotlight Image
	 */
	
	$actspot = new MastopFormSelectImage ( '', 'options[6]', $options [6] );
	$form .= '<tr>';
	$form .= '<td>' . _MB_IMREPORTING_ARTICLE_IMAGESPOTLIGHT . '</td>';
	$form .= '<td>' . $actspot->render () . '</td>';
	$form .= '</tr>';
	
	$form .= '<tr><td colspan="2" align="center"><h3 style="margin-bottom:0;">' . _MB_IMREPORTING_ARTICLE_FILTERS_CONFIGS . '</h3></td></tr>';
	/**
	 * Filter by Users
	 */
	$users = $imreporting_article_handler->getPostersArray ();
	$fromusers = new XoopsFormSelect ( '', 'options[7]', $options [7], 1 );
	$fromusers->addOption ( 0, _MB_IMREPORTING_ARTICLE_ALLPOSTERS );
	$fromusers->addOptionArray ( $users );
	$form .= '<tr>';
	$form .= '<td>' . _MB_IMREPORTING_ARTICLE_POSTERS . '</td>';
	$form .= '<td>' . $fromusers->render () . '</td>';
	$form .= '</tr>';
	
	/**
	 * Filter by Categories
	 */
	$categories = $imreporting_article_handler->getCategoriesArray ();
	$values = array ( );
	for($i = 8; $i < $size; $i ++) {
		$values [] = $options [$i];
	}
	$fromcats = new XoopsFormSelect ( '', 'options[8]', $values, 5, true );
	$fromcats->addOption ( 0, _MB_IMREPORTING_ARTICLE_ALLCATEGORIES );
	$fromcats->addOptionArray ( $categories );
	$form .= '<tr>';
	$form .= '<td>' . _MB_IMREPORTING_ARTICLE_CATEGORIES . '</td>';
	$form .= '<td>' . $fromcats->render () . '</td>';
	$form .= '</tr>';
	
	$form .= '</table>';
	
	return $form;
}

?>