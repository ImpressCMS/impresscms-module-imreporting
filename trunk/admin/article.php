<?php
/**
* Admin page to manage articles
*
* List, add, edit and delete article objects
*
* @copyright	
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Rodrigo Lima aka TheRplima <therplima@impresscms.org>
* @package		imreporting
* @version		$Id$
*/

/**
 * Edit a Article
 *
 * @param int $article_id Articleid to be edited
*/
function editarticle($article_id = 0)
{
	global $imreporting_article_handler, $xoopsModule, $icmsAdminTpl;

	$articleObj = $imreporting_article_handler->get($article_id);

	if (!$articleObj->isNew()){
		$xoopsModule->displayAdminMenu(0, _AM_IMREPORTING_ARTICLES . " > " . _CO_ICMS_EDITING);
		$articleObj->loadCategories();
		$sform = $articleObj->getForm(_AM_IMREPORTING_ARTICLE_EDIT, 'addarticle');
		$sform->assign($icmsAdminTpl);

	} else {
		$xoopsModule->displayAdminMenu(0, _AM_IMREPORTING_ARTICLES . " > " . _CO_ICMS_CREATINGNEW);
		$sform = $articleObj->getForm(_AM_IMREPORTING_ARTICLE_CREATE, 'addarticle');
		$sform->assign($icmsAdminTpl);

	}
	$icmsAdminTpl->display('db:imreporting_admin_article.html');
}

$icmsOnDemandPreload[] = array(
	'module'=>'imtagging',
	'filename'=>'jquery.php'
);
$icmsOnDemandPreload[] = array(
	'module'=>'imtagging',
	'filename'=>'imtaggingadmincss.php'
);

include_once("admin_header.php");

$imreporting_article_handler = xoops_getModuleHandler('article');
/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod','changedField','addarticle', 'addcategory','del','');

if (isset($_GET['op'])) $clean_op = htmlentities($_GET['op']);
if (isset($_POST['op'])) $clean_op = htmlentities($_POST['op']);

/** Again, use a naming convention that indicates the source of the content of the variable */
$clean_article_id = isset($_GET['article_id']) ? (int) $_GET['article_id'] : 0 ;
$clean_article_id = isset($_POST['article_id']) ? (int) $_POST['article_id'] : $clean_article_id;
$clean_category_pid = isset($_POST['category_pid']) ? (int) $_POST['category_pid'] : 0 ;
/**
 * in_array() is a native PHP function that will determine if the value of the
 * first argument is found in the array listed in the second argument. Strings
 * are case sensitive and the 3rd argument determines whether type matching is
 * required
*/
if (in_array($clean_op,$valid_op,true)){
  switch ($clean_op) {
  	case "addcategory":
  		// the logger needs to be disabled in an AJAX request
  		$xoopsLogger->disableLogger();

		// adding the new category
		$imtagging_category_handler = xoops_getModuleHandler('category', 'imtagging');
		$categoryObj = $imtagging_category_handler->create();
		$categoryObj->setVar('category_title', $_POST['category_title']);
		$categoryObj->setVar('category_pid', $clean_category_pid);
		$imtagging_category_handler->insert($categoryObj);

		// rebuild the ImtaggingCategoryTreeElement control
		$postObj = $imreporting_article_handler->get($clean_article_id);

		include_once(ICMS_ROOT_PATH . "/class/xoopsformloader.php");
		include_once(ICMS_ROOT_PATH . '/modules/imtagging/class/form/elements/imtaggingcategorytreeelement.php');
		$category_tree_element = new ImtaggingCategoryTreeElement($postObj, 'categories');
		echo $category_tree_element->render();
		exit;
  	break;
  	case "mod":
  	case "changedField":

  		xoops_cp_header();

  		editarticle($clean_article_id);
  		break;
  	case "addarticle":
          include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
          $controller = new IcmsPersistableController($imreporting_article_handler);
  		$controller->storeFromDefaultForm(_AM_IMREPORTING_ARTICLE_CREATED, _AM_IMREPORTING_ARTICLE_MODIFIED);

  		break;
  	case "del":
  	    include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
          $controller = new IcmsPersistableController($imreporting_article_handler);
  		$controller->handleObjectDeletion();

  		break;
  	default:

  		xoops_cp_header();

  		$xoopsModule->displayAdminMenu(0, _AM_IMREPORTING_ARTICLES);

  		include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";
  		$objectTable = new IcmsPersistableTable($imreporting_article_handler);
  		$objectTable->addColumn(new IcmsPersistableColumn('article_title', _GLOBAL_LEFT));
  		$objectTable->addColumn(new IcmsPersistableColumn('categories', 'center', 150, 'showCategoriesList'));
  		$objectTable->addColumn(new IcmsPersistableColumn('article_published_date', 'center', 150));
  		$objectTable->addColumn(new IcmsPersistableColumn('article_uid', 'center', 150));
  		$objectTable->addColumn(new IcmsPersistableColumn('article_status', 'center', 150));
  		$objectTable->addColumn(new IcmsPersistableColumn('counter', 'center', 150));
  		
  		$objectTable->addIntroButton('addarticle', 'article.php?op=mod', _AM_IMREPORTING_ARTICLE_CREATE);
  		$objectTable->addQuickSearch(array('article_title', 'article_content'));

  		$objectTable->addFilter('article_status', 'getArticle_statusArray');
  		$objectTable->addFilter('article_uid', 'getPostersArray');
  		
  		$icmsAdminTpl->assign('imreporting_article_table', $objectTable->fetch());
  		$icmsAdminTpl->display('db:imreporting_admin_article.html');
  		break;
  }
  xoops_cp_footer();
}
/**
 * If you want to have a specific action taken because the user input was invalid,
 * place it at this point. Otherwise, a blank page will be displayed
 */
?>