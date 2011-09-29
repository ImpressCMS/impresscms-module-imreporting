<?php
/**
 * Article page
 *
 * @copyright	
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Rodrigo Lima aka TheRplima <therplima@impresscms.org>
 * @package		imreporting
 * @version		$Id$
 */

/**
 * Edit an Article
 *
 * @param object $articleObj ImreportingArticle object to be edited
 */
function editarticle($articleObj) {
	global $imreporting_article_handler, $xoTheme, $xoopsTpl, $xoopsUser, $xoopsModuleConfig, $imreporting_isAdmin;
	
	$articleObj->setControl ( 'categories', array ('name' => 'categories', 'module' => 'imtagging', 'userside' => true ) );
	
	if (! $articleObj->isNew ()) {
		if (! $articleObj->userCanEditAndDelete ()) {
			redirect_header ( $articleObj->getItemLink ( true ), 3, _NOPERM );
		}
		$articleObj->loadCategories ();
		$hiddenFields = array ('article_published_date', 'article_uid', 'meta_keywords', 'meta_description', 'short_url' );
		if (!$imreporting_isAdmin){
			$hiddenFields[] = 'article_status';
		}
		$articleObj->hideFieldFromForm ( $hiddenFields );
		$sform = $articleObj->getSecureForm ( _MD_IMREPORTING_ARTICLE_EDIT, 'addarticle' );
		$sform->assign ( $xoopsTpl, 'imreporting_articleform' );
		$xoopsTpl->assign ( 'imreporting_category_path', $articleObj->getVar ( 'article_title' ) . ' > ' . _EDIT );
	} else {
		if (! $imreporting_article_handler->userCanSubmit ()) {
			redirect_header ( IMBLOGGING_URL, 3, _NOPERM );
		}
		$hiddenFields = array ('article_published_date', 'article_uid', 'meta_keywords', 'meta_description', 'short_url' );
		if (!$xoopsModuleConfig['articles_autopublish']){
			$articleObj->setVar ( 'article_status', IMREPORTING_ARTICLE_STATUS_PENDING );
		}
		if (!$imreporting_isAdmin){
			$hiddenFields[] = 'article_status';
		}
		$articleObj->setVar ( 'article_uid', $xoopsUser->uid () );
		$articleObj->setVar ( 'article_published_date', time () );
		$articleObj->hideFieldFromForm ( $hiddenFields );
		$sform = $articleObj->getSecureForm ( _MD_IMREPORTING_ARTICLE_SUBMIT, 'addarticle' );
		$sform->assign ( $xoopsTpl, 'imreporting_articleform' );
		$xoopsTpl->assign ( 'imreporting_category_path', _SUBMIT );
	}
	
	$xoTheme->addStylesheet ( ICMS_URL . '/modules/imtagging/module' . ((defined ( "_ADM_USE_RTL" ) && _ADM_USE_RTL) ? '_rtl' : '') . '.css' );
}

include_once 'header.php';

$xoopsOption ['template_main'] = 'imreporting_article.html';
include_once ICMS_ROOT_PATH . '/header.php';

$imreporting_article_handler = xoops_getModuleHandler ( 'article' );

/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';

if (isset ( $_GET ['op'] ))
	$clean_op = $_GET ['op'];
if (isset ( $_POST ['op'] ))
	$clean_op = $_POST ['op'];

/** Use a naming convention that indicates the source of the content of the variable */
$clean_article_id = isset ( $_GET ['article_id'] ) ? intval ( $_GET ['article_id'] ) : 0;
$clean_start = isset($_GET['start']) ? intval($_GET['start']) : 0;
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod', 'addarticle', 'del', '' );

/**
 * Only proceed if the supplied operation is a valid operation
 */
if (in_array ( $clean_op, $valid_op, true )) {
	switch ( $clean_op) {
		case "mod" :
			$articleObj = $imreporting_article_handler->get ( $clean_article_id );
			if ($clean_article_id > 0 && $articleObj->isNew ()) {
				redirect_header ( imreporting_getPreviousPage ( 'index.php' ), 3, _NOPERM );
			}
			$xoopsTpl->assign ( 'imreporting_article', false );
			$xoopsTpl->assign('articles_showbreadcrumb', $xoopsModuleConfig['articles_showbreadcrumb']);
			editarticle ( $articleObj );
		break;
		
		case "addarticle" :
			if (! $xoopsSecurity->check ()) {
				redirect_header ( imreporting_getPreviousPage ( 'index.php' ), 3, _MD_IMREPORTING_SECURITY_CHECK_FAILED . implode ( '<br />', $xoopsSecurity->getErrors () ) );
			}
			include_once ICMS_ROOT_PATH . '/kernel/icmspersistablecontroller.php';
			$controller = new IcmsPersistableController ( $imreporting_article_handler );
			$controller->storeFromDefaultForm ( _MD_IMREPORTING_ARTICLE_CREATED, _MD_IMREPORTING_ARTICLE_MODIFIED );
		break;
		
		case "del" :
			$articleObj = $imreporting_article_handler->get ( $clean_article_id );
			if (! $articleObj->userCanEditAndDelete ()) {
				redirect_header ( $articleObj->getItemLink ( true ), 3, _NOPERM );
			}
			if (isset ( $_POST ['confirm'] )) {
				if (! $xoopsSecurity->check ()) {
					redirect_header ( $impresscms->urls ['previouspage'], 3, _MD_IMREPORTING_SECURITY_CHECK_FAILED . implode ( '<br />', $xoopsSecurity->getErrors () ) );
				}
			}
			include_once ICMS_ROOT_PATH . '/kernel/icmspersistablecontroller.php';
			$controller = new IcmsPersistableController ( $imreporting_article_handler );
			$controller->handleObjectDeletionFromUserSide ();
			$xoopsTpl->assign ( 'imreporting_article', false );
			$xoopsTpl->assign ( 'imreporting_category_path', $articleObj->getVar ( 'article_title' ) . ' > ' . _DELETE );
			$xoopsTpl->assign('articles_showbreadcrumb', $xoopsModuleConfig['articles_showbreadcrumb']);
		
		break;
		
		default :
			$articleObj = $imreporting_article_handler->get ( $clean_article_id );
			if ($articleObj && ! $articleObj->isNew ()) {
				$articleArray = $articleObj->toArray ();
				$imreporting_article_handler->updateCounter ( $clean_article_id );
				$xoopsTpl->assign ( 'imreporting_article', $articleArray );
				$xoopsTpl->assign ( 'imreporting_category_path', $articleArray ['article_title'] );
				
				$xoopsTpl->assign ( 'imreporting_showSubmitLink', true );
				$xoopsTpl->assign ( 'imreporting_rss_url', IMREPORTING_URL . 'rss.php' );
				$xoopsTpl->assign ( 'imreporting_rss_info', _MD_IMREPORTING_RSS_GLOBAL );
				
				if ($xoopsModuleConfig ['com_rule'] && $articleArray ['article_cancomment']) {
					$xoopsTpl->assign ( 'imreporting_article_comment', true );
					include_once ICMS_ROOT_PATH . '/include/comment_view.php';
				}
				/**
				 * Generating meta information for this page
				 */
				$icms_metagen = new IcmsMetagen ( $articleArray ['article_title'], $articleArray ['meta_keywords'], $articleArray ['meta_description'] );
				$icms_metagen->createMetaTags ();
			}else{
				$xoopsTpl->assign('imreporting_articles', $imreporting_article_handler->getArticles($clean_start, $xoopsModuleConfig['articles_limit'], false, false, false, false));
			}
			$xoopsTpl->assign('articles_showbreadcrumb', $xoopsModuleConfig['articles_showbreadcrumb']);
			$xoopsTpl->assign('change_date_format', (isset($xoopsModuleConfig['date_format']) && $xoopsModuleConfig['date_format'] != '')?true:false);
		break;
	}
}

$xoopsTpl->assign ( 'imreporting_module_home', imreporting_getModuleName ( true, true ) );

include_once 'footer.php';
?>