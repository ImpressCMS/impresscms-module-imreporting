<?php

/**
 * Classes responsible for managing imReporting article objects
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
	
// including the IcmsPersistabelSeoObject
include_once ICMS_ROOT_PATH . '/kernel/icmspersistableseoobject.php';
include_once (ICMS_ROOT_PATH . '/modules/imreporting/include/functions.php');

/**
 * Article status definitions
 */
define ( 'IMREPORTING_ARTICLE_STATUS_PUBLISHED', 1 );
define ( 'IMREPORTING_ARTICLE_STATUS_PENDING', 2 );
define ( 'IMREPORTING_ARTICLE_STATUS_DRAFT', 3 );
define ( 'IMREPORTING_ARTICLE_STATUS_PRIVATE', 4 );
define ( 'IMREPORTING_ARTICLE_STATUS_EXPIRED', 5 );

class ImreportingArticle extends IcmsPersistableSeoObject {
	
	private $article_date_info = false;
	private $poster_info = false;
	public $updating_counter = false;
	public $categories = false;
	
	/**
	 * Constructor
	 *
	 * @param object $handler ImreportingArticleHandler object
	 */
	public function __construct(& $handler) {
		global $xoopsConfig;
		
		$this->IcmsPersistableObject ( $handler );
		
		$this->quickInitVar ( 'article_id', XOBJ_DTYPE_INT, true );
		$this->quickInitVar ( 'article_title', XOBJ_DTYPE_TXTBOX, true );
		$this->quickInitVar ( 'article_uid', XOBJ_DTYPE_INT, false );
		
		/**
		 * @todo IPF needs to be able to know what to do with XOBJ_DTYPE_ARRAY, which it does not right now...
		 */
		$this->initNonPersistableVar ( 'categories', XOBJ_DTYPE_INT, 'category', false, false, false, true );
		
		$this->quickInitVar ( 'article_summary', XOBJ_DTYPE_TXTAREA, false );
		$this->quickInitVar ( 'article_content', XOBJ_DTYPE_TXTAREA, false );
		$this->quickInitVar ( 'article_published_date', XOBJ_DTYPE_LTIME, false );
		$this->quickInitVar ( 'article_expired_date', XOBJ_DTYPE_LTIME, false );
		$this->quickInitVar ( 'article_status', XOBJ_DTYPE_INT, false, false, false, IMREPORTING_ARTICLE_STATUS_PUBLISHED );
		$this->quickInitVar ( 'article_cancomment', XOBJ_DTYPE_INT, false, false, false, true );
		$this->quickInitVar ( 'article_include_summary', XOBJ_DTYPE_INT, false, false, false, true );
		
		$this->quickInitVar ( 'article_comments', XOBJ_DTYPE_INT );
		$this->hideFieldFromForm ( 'article_comments' );
		
		$this->quickInitVar ( 'article_notification_sent', XOBJ_DTYPE_INT );
		$this->hideFieldFromForm ( 'article_notification_sent' );
		
		$this->initCommonVar ( 'counter', false );
		$this->initCommonVar ( 'dohtml', false, true );
		$this->initCommonVar ( 'dobr' );
		$this->initCommonVar ( 'doimage', false, true );
		$this->initCommonVar ( 'dosmiley', false, true );
		$this->initCommonVar ( 'doxcode', false, true );
		
		$this->setControl ( 'categories', array ('name' => 'categories', 'module' => 'imtagging' ) );
		$this->setControl ( 'article_summary', 'dhtmltextarea' );
		$this->setControl ( 'article_content', 'dhtmltextarea' );
		$this->setControl ( 'article_uid', 'user' );
		$this->setControl ( 'article_status', array ('itemHandler' => 'article', 'method' => 'getArticle_statusArray', 'module' => 'imreporting' ) );
		
		$this->setControl ( 'article_cancomment', 'yesno' );
		$this->setControl ( 'article_include_summary', 'yesno' );
		
		$this->IcmsPersistableSeoObject ();
	}
	
	/**
	 * Overriding the IcmsPersistableObject::getVar method to assign a custom method on some
	 * specific fields to handle the value before returning it
	 *
	 * @param str $key key of the field
	 * @param str $format format that is requested
	 * @return mixed value of the field that is requested
	 */
	function getVar($key, $format = 's') {
		if ($format == 's' && in_array ( $key, array ('article_uid', 'article_status', 'categories' ) )) {
			return call_user_func ( array ($this, $key ) );
		} elseif ($format == 'e' && in_array ( $key, array ('categories' ) )) {
			return call_user_func ( array ($this, $key ), 2 );
		}
		return parent::getVar ( $key, $format );
	}
	
	/**
	 * Load categories linked to this article
	 *
	 * @return void
	 */
	function loadCategories() {
		$imtagging_category_link_handler = xoops_getModuleHandler ( 'category_link', 'imtagging' );
		$ret = $imtagging_category_link_handler->getCategoriesForObject ( $this->id (), $this->handler );
		$this->setVar ( 'categories', $ret );
	}
	
	function showCategoriesList() {
		$this->loadCategories ();
		$catids = $this->vars ['categories'] ['value'];
		$imtagging_category_handler = xoops_getModuleHandler ( 'category', 'imtagging' );
		$ret = '';
		if (is_array ( $catids )) {
			foreach ( $catids as $catid ) {
				$catObj = $imtagging_category_handler->get ( $catid );
				$ret .= '<li><a href="' . IMREPORTING_URL . 'index.php?cid=' . $catObj->getVar ( 'category_id' ) . '">' . $catObj->getVar ( 'category_title' ) . '</a></li>';
			}
			$ret = '<ul>' . $ret . '</ul>';
			return $ret;
		} else {
			$catObj = $imtagging_category_handler->get ( $catids );
			if (is_object ( $catObj )) {
				$ret .= '<ul><li><a href="' . IMREPORTING_URL . 'index.php?cid=' . $catObj->getVar ( 'category_id' ) . '">' . $catObj->getVar ( 'category_title' ) . '</a></li></ul>';
			} else {
				return false;
			}
		}
	}
	
	function categories() {
		$ret = $this->getVar ( 'categories', 'n' );
		$ret = $this->vars ['categories'] ['value'];
		if (is_array ( $ret )) {
			return $ret;
		} else {
			( int ) $ret > 0 ? array (( int ) $ret ) : false;
		}
	}
	
	/**
	 * Retrieving the name of the poster, linked to his profile
	 *
	 * @return str name of the poster
	 */
	function article_uid() {
		return imreporting_getLinkedUnameFromId ( $this->getVar ( 'article_uid', 'e' ) );
	}
	
	/**
	 * Retrieving the status of the article
	 *
	 * @param str status of the article
	 * @return mixed $article_statusArray[$ret] status of the article
	 */
	function article_status() {
		$ret = $this->getVar ( 'article_status', 'e' );
		$article_statusArray = $this->handler->getArticle_statusArray ();
		return $article_statusArray [$ret];
	}
	
	/**
	 * Returns the need to br
	 *
	 * @return bool true | false
	 */
	function need_do_br() {
		global $xoopsConfig, $xoopsUser;
		
		$imreporting_module = icms_getModuleInfo ( 'imreporting' );
		$groups = $xoopsUser->getGroups ();
		
		$editor_default = $xoopsConfig ['editor_default'];
		$gperm_handler = xoops_getHandler ( 'groupperm' );
		if (file_exists ( ICMS_EDITOR_PATH . "/" . $editor_default . "/xoops_version.php" ) && $gperm_handler->checkRight ( 'use_wysiwygeditor', $imreporting_module->mid (), $groups )) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * Check is user has access to view this article
	 *
	 * User will be able to view the article if
	 *    - the status of the article is Published OR
	 *    - he is an admin OR
	 * 	  - he is the poster of this article
	 *
	 * @return bool true if user can view this article, false if not
	 */
	function accessGranted() {
		global $imreporting_isAdmin, $xoopsUser;
		return $this->getVar ( 'article_status', 'e' ) == IMREPORTING_ARTICLE_STATUS_PUBLISHED || $imreporting_isAdmin || $this->getVar ( 'article_uid', 'e' ) == $xoopsUser->uid ();
	}
	
	/**
	 * Get the poster
	 *
	 * @param bool $link with link or not
	 * @return str poster name linked on his module poster page, or simply poster name
	 */
	function getPoster($link = false) {
		if (! $this->poster_info) {
			$member_handler = xoops_getHandler ( 'member' );
			$poster_uid = $this->getVar ( 'article_uid', 'e' );
			$userObj = $member_handler->getuser ( $poster_uid );
			
			/**
			 * We need to make sure the poster is a valid user object. It is possible the user no longer
			 * exists if, for example, he was previously deleted. In that case, we will return Anonymous
			 */
			if (is_object ( $userObj )) {
				$this->poster_info ['uid'] = $poster_uid;
				$this->poster_info ['uname'] = $userObj->getVar ( 'uname' );
				$this->poster_info ['link'] = '<a href="' . IMREPORTING_URL . 'index.php?uid=' . $this->poster_info ['uid'] . '">' . $this->poster_info ['uname'] . '</a>';
			} else {
				global $xoopsConfig;
				$this->poster_info ['uid'] = 0;
				$this->poster_info ['uname'] = $xoopsConfig ['anonymous'];
			}
		}
		if ($link && $this->poster_info ['uid']) {
			return $this->poster_info ['link'];
		} else {
			return $this->poster_info ['uname'];
		}
	}
	
	/**
	 * Retrieve article info (poster and date)
	 *
	 * @return str article info
	 */
	function getArticleInfo() {
		$status = $this->getVar ( 'article_status', 'e' );
		switch ( $status) {
			case IMREPORTING_ARTICLE_STATUS_PENDING :
				$ret = _CO_IMREPORTING_ARTICLE_PENDING;
			break;
			
			case IMREPORTING_ARTICLE_STATUS_DRAFT :
				$ret = _CO_IMREPORTING_ARTICLE_DRAFT;
			break;
			
			case IMREPORTING_ARTICLE_STATUS_PRIVATE :
				$ret = _CO_IMREPORTING_ARTICLE_PRIVATE;
			break;
			
			case IMREPORTING_ARTICLE_STATUS_EXPIRED :
				$ret = _CO_IMREPORTING_ARTICLE_EXPIRED;
			break;
			
			default :
				$ret = _CO_IMREPORTING_ARTICLE_PUBLISHED;
			break;
		}
		return $ret;
	}
	
	/**
	 * Retrieve article comment info (number of comments)
	 *
	 * @return str article comment info
	 */
	function getCommentsInfo() {
		$article_comments = $this->getVar ( 'article_comments' );
		if ($article_comments) {
			return '<a href="' . $this->getItemLink ( true ) . '#comments_container">' . sprintf ( _CO_IMREPORTING_ARTICLE_COMMENTS_INFO, $article_comments ) . '</a>';
		} else {
			return _CO_IMREPORTING_ARTICLE_NO_COMMENT;
		}
	}
	
	/**
	 * Get article year, month and day and assign value to proper var
	 *
	 * @return VOID
	 */
	function getArticleDateInfo() {
		global $xoopsConfig;
		$article_date = $this->getVar ( 'article_published_date', 'n' );
		$this->article_date_info ['year'] = formatTimestamp ( $article_date, 'Y' );
		$this->article_date_info ['month'] = imreporting_getMonthNameById ( formatTimestamp ( $article_date, 'n' ) );
		$this->article_date_info ['month_short'] = formatTimestamp ( $article_date, 'month' );
		$this->article_date_info ['day'] = formatTimestamp ( $article_date, 'D' );
		$this->article_date_info ['day_number'] = formatTimestamp ( $article_date, 'daynumber' );
	}
	
	/**
	 * Get year of this article
	 *
	 * @return int year of this article
	 */
	function getArticleYear() {
		if (! $this->article_date_info) {
			$this->getArticleDateInfo ();
		}
		return $this->article_date_info ['year'];
	}
	
	/**
	 * Get month of this article
	 *
	 * @return str month of this article
	 */
	function getArticleMonth() {
		if (! $this->article_date_info) {
			$this->getArticleDateInfo ();
		}
		return $this->article_date_info ['month'];
	}
	
	/**
	 * Get month short name of this article
	 *
	 * @return str month of this article
	 */
	function getArticleMonthShort() {
		if (! $this->article_date_info) {
			$this->getArticleDateInfo ();
		}
		return $this->article_date_info ['month_short'];
	}
	
	/**
	 * Get day of this article
	 *
	 * @return str day of this article
	 */
	function getArticleDay() {
		if (! $this->article_date_info) {
			$this->getArticleDateInfo ();
		}
		return $this->article_date_info ['day'];
	}
	
	/**
	 * Get day number of this article
	 *
	 * @return str day number of this article
	 */
	function getArticleDayNumber() {
		if (! $this->article_date_info) {
			$this->getArticleDateInfo ();
		}
		return $this->article_date_info ['day_number'];
	}
	
	/**
	 * Check to see wether the current user can edit or delete this article
	 *
	 * @return bool true if he can, false if not
	 */
	function userCanEditAndDelete() {
		global $xoopsUser, $imreporting_isAdmin;
		if (! is_object ( $xoopsUser )) {
			return false;
		}
		if ($imreporting_isAdmin) {
			return true;
		}
		return $this->getVar ( 'article_uid', 'e' ) == $xoopsUser->uid ();
	}
	
	/**
	 * Sending the notification related to a article being published
	 *
	 * @return VOID
	 */
	function sendNotifArticlePublished() {
		global $imreportingModule;
		$module_id = $imreportingModule->getVar ( 'mid' );
		$notification_handler = xoops_getHandler ( 'notification' );
		
		$tags ['ARTICLE_TITLE'] = $this->getVar ( 'article_title' );
		$tags ['ARTICLE_URL'] = $this->getItemLink ( true );
		
		$notification_handler->triggerEvent ( 'global', 0, 'article_published', $tags, array ( ), $module_id );
	}
	
	/**
	 * Overridding IcmsPersistable::toArray() method to add a few info
	 *
	 * @return array of article info
	 */
	function toArray() {
		global $imreportingConfig;
		
		$ret = parent::toArray ();
		$ret ['article_info'] = $this->getArticleInfo ();
		if (isset($imreportingConfig['date_format']) && $imreportingConfig['date_format'] != ''){
			$ret ['article_published_date'] = formatTimestamp($this->getVar ( 'article_published_date', 'e' ),$imreportingConfig['date_format']);
		}
		$ret ['article_published_date_int'] = $this->getVar ( 'article_published_date', 'e' );
		$ret ['article_year'] = $this->getArticleYear ();
		$ret ['article_month'] = $this->getArticleMonth ();
		$ret ['article_month_short'] = $this->getArticleMonthShort ();
		$ret ['article_day'] = $this->getArticleDay ();
		$ret ['article_day_number'] = $this->getArticleDayNumber ();
		$ret ['article_comment_info'] = $this->getCommentsInfo ();
		$ret ['editItemLink'] = $this->getEditItemLink ( false, true, true );
		$ret ['deleteItemLink'] = $this->getDeleteItemLink ( false, true, true );
		$ret ['userCanEditAndDelete'] = $this->userCanEditAndDelete ();
		$ret ['article_posterid'] = $this->getVar ( 'article_uid', 'e' );
		$ret ['article_poster_link'] = $this->getPoster ( true );
		$this->loadCategories();
		$ret ['categories_array'] = $this->categories (  );

		return $ret;
	}
}
class ImreportingArticleHandler extends IcmsPersistableObjectHandler {
	
	/**
	 * @var array of status
	 */
	var $_article_statusArray = array ( );
	
	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		$this->IcmsPersistableObjectHandler ( $db, 'article', 'article_id', 'article_title', 'article_content', 'imreporting' );
	}
	
	/**
	 * Retreive the possible status of a article object
	 *
	 * @return array of status
	 */
	function getArticle_statusArray() {
		if (! $this->_article_statusArray) {
			$this->_article_statusArray [IMREPORTING_ARTICLE_STATUS_PUBLISHED] = _CO_IMREPORTING_ARTICLE_STATUS_PUBLISHED;
			$this->_article_statusArray [IMREPORTING_ARTICLE_STATUS_PENDING] = _CO_IMREPORTING_ARTICLE_STATUS_PENDING;
			$this->_article_statusArray [IMREPORTING_ARTICLE_STATUS_DRAFT] = _CO_IMREPORTING_ARTICLE_STATUS_DRAFT;
			$this->_article_statusArray [IMREPORTING_ARTICLE_STATUS_PRIVATE] = _CO_IMREPORTING_ARTICLE_STATUS_PRIVATE;
			$this->_article_statusArray [IMREPORTING_ARTICLE_STATUS_EXPIRED] = _CO_IMREPORTING_ARTICLE_STATUS_EXPIRED;
		}
		return $this->_article_statusArray;
	}
	
	/**
	 * Retreive the possible categories of an article object
	 *
	 * @return array of categories
	 */
	function getCategoriesArray() {
		$categories = array ( );
		$articles = $this->getArticles ();
		// retrieve the ids of all Articles retrieved
		$articleIds = $this->getIdsFromObjectsAsArray ( $articles );
		
		// retrieve categories linked to these articleIds
		$imtagging_category_link_handler = xoops_getModuleHandler ( 'category_link', 'imtagging' );
		$categoriesObj = $imtagging_category_link_handler->getCategoriesFromObjectIds ( $articleIds, $this );
		foreach ( $categoriesObj as $categoryObj ) {
			$categories [$categoryObj->getVar ( 'category_id' )] = $categoryObj->getVar ( 'category_title' );
		}
		
		return $categories;
	}
	
	/**
	 * Create the criteria that will be used by getArticles and getArticlesCount
	 *
	 * @param int $start to which record to start
	 * @param int $limit limit of articles to return
	 * @param int $article_uid if specifid, only the article of this user will be returned
	 * @param int $cid if specifid, only the article related to this category will be returned
	 * @param int $year of articles to display
	 * @param int $month of articles to display
	 * @param int $article_id ID of a single article to retrieve
	 * @return CriteriaCompo $criteria
	 */
	function getArticlesCriteria($start = 0, $limit = 0, $article_uid = false, $cid = false, $year = false, $month = false, $article_id = false, $order = 'article_published_date', $sort = 'DESC') {
		global $xoopsUser;
		
		$criteria = new CriteriaCompo ( );
		if ($start) {
			$criteria->setStart ( $start );
		}
		if ($limit) {
			$criteria->setLimit ( intval ( $limit ) );
		}
		$criteria->setSort ( $order );
		$criteria->setOrder ( $sort );
		
		$criteria->add ( new Criteria ( 'article_status', IMREPORTING_ARTICLE_STATUS_PUBLISHED ) );
		
		if ($article_uid) {
			$criteria->add ( new Criteria ( 'article_uid', $article_uid ) );
		}
		if ($cid) {
			$imtagging_category_link_handler = xoops_getModuleHandler ( 'category_link', 'imtagging' );
			if (is_array($cid)){
				if (count($cid) == 1 && $cid[0] == 0){
					$cid = $this->getCategoriesArray();
					$cid = array_keys($cid);
				}
				$categoriesids = array();
				foreach ($cid as $cat){
					$categoryids = $imtagging_category_link_handler->getItemidsForCategory ( $cat, $this );
					foreach ($categoryids as $categoryid){
						$categoriesids[$categoryid] = $categoryid;
					}
				}
				$criteria->add ( new Criteria ( 'article_id', '(' . implode ( ',', $categoriesids ) . ')', 'IN' ) );
			}else{
				$categoryids = $imtagging_category_link_handler->getItemidsForCategory ( $cid, $this );
				$criteria->add ( new Criteria ( 'article_id', '(' . implode ( ',', $categoryids ) . ')', 'IN' ) );
			}
		}
		
		if ($year && $month) {
			$criteriaYearMonth = new CriteriaCompo ( );
			$criteriaYearMonth->add ( new Criteria ( 'MONTH(FROM_UNIXTIME(article_published_date))', $month ) );
			$criteriaYearMonth->add ( new Criteria ( 'YEAR(FROM_UNIXTIME(article_published_date))', $year ) );
			$criteria->add ( $criteriaYearMonth );
		}
		if ($article_id) {
			$criteria->add ( new Criteria ( 'article_id', $article_id ) );
		}
		
		return $criteria;
	}
	
	/**
	 * Get single article object
	 *
	 * @param int $article_id
	 * @return object ImreportingArticle object
	 */
	function getArticle($article_id) {
		$ret = $this->getArticles ( 0, 0, false, false, false, false, $article_id );
		return isset ( $ret [$article_id] ) ? $ret [$article_id] : false;
	}
	
	/**
	 * Get articles as array, ordered by article_published_date DESC
	 *
	 * @param int $start to which record to start
	 * @param int $limit max articles to display
	 * @param int $article_uid if specifid, only the article of this user will be returned
	 * @param int $cid if specifid, only the article related to this category will be returned
	 * @param int $year of articles to display
	 * @param int $month of articles to display
	 * @param int $article_id ID of a single article to retrieve
	 * @return array of articles
	 */
	function getArticles($start = 0, $limit = 0, $article_uid = false, $cid = false, $year = false, $month = false, $article_id = false, $order = 'article_published_date', $sort = 'DESC') {
		$criteria = $this->getArticlesCriteria ( $start, $limit, $article_uid, $cid, $year, $month, $article_id, $order, $sort );
		$ret = $this->getObjects ( $criteria, true, false );
		
		// retrieve the ids of all Articles retrieved
		$articleIds = $this->getIdsFromObjectsAsArray ( $ret );
		
		// retrieve categories linked to these articleIds
		$imtagging_category_link_handler = xoops_getModuleHandler ( 'category_link', 'imtagging' );
		$categoriesObj = $imtagging_category_link_handler->getCategoriesFromObjectIds ( $articleIds, $this );
		
		// put the category info in each articleObj
		foreach ( $categoriesObj as $categoryObj ) {
			if (isset ( $categoryObj->items ['imreporting'] ['article'] ))
				foreach ( $categoryObj->items ['imreporting'] ['article'] as $articleid ) {
					$ret [$articleid] ['categories'] [] = array ('id' => $categoryObj->id (), 'title' => $categoryObj->getVar ( 'category_title' ) );
				}
		}
		
		return $ret;
	}
	
	function getIdsFromObjectsAsArray($objs) {
		$ret = array ( );
		
		foreach ( $objs as $k => $obj ) {
			$ret [] = $k;
		}
		
		return $ret;
	}
	
	/**
	 * Get a list of users
	 *
	 * @return array list of users
	 */
	function getPostersArray() {
		$member_handler = xoops_getHandler ( 'member' );
		return $member_handler->getUserList ();
	}
	
	/**
	 * Get articles count
	 *
	 * @param int $article_uid if specifid, only the article of this user will be returned
	 * @param int $cid if specifid, only the article related to this category will be returned
	 * @return array of articles
	 * @param int $year of articles to display
	 * @param int $month of articles to display
	 */
	function getArticlesCount($article_uid, $cid = false, $year = false, $month = false) {
		$criteria = $this->getArticlesCriteria ( false, false, $article_uid, $cid, $year, $month );
		return $this->getCount ( $criteria );
	}
	
	function getArticlesCountByMonth() {
		$sql = 'SELECT count(article_id) AS articles_count, MONTH(FROM_UNIXTIME(article_published_date)) AS articles_month, YEAR(FROM_UNIXTIME(article_published_date)) AS articles_year ' . 'FROM ' . $this->table . ' ' . 'GROUP BY articles_year, articles_month ' . 'HAVING articles_count > 0 ' . 'ORDER BY articles_year DESC, articles_month DESC';
		$articlesByMonthArray = $this->query ( $sql, false );
		$ret = array ( );
		$config_handler = & xoops_gethandler ( 'config' );
		$xoopsConfig = & $config_handler->getConfigsByCat ( XOOPS_CONF );
		foreach ( $articlesByMonthArray as $articleByMonth ) {
			$articleByMonthnr = $articleByMonth ['articles_month'];
			$articleByYearname = $articleByMonth ['articles_year'];
			$articleByYearnr = $articleByMonth ['articles_year'];
			if ($xoopsConfig ['language'] == "persian" && $xoopsConfig ['use_ext_date'] == 1) {
				include_once ICMS_ROOT_PATH . '/language/' . $xoopsConfig ['language'] . '/calendar.php';
				$gyear = $articleByYearname;
				$gmonth = $articleByMonthnr;
				$gday = 1;
				list ( $jyear, $jmonth, $jday ) = gregorian_to_jalali ( $gyear, $gmonth, $gday );
				$articleByYearname = icms_conv_nr2local ( $jyear );
				$articleByYearnr = $jyear;
				$articleByMonthnr = $jmonth;
			
			}
			$articleByMonth ['articles_year_nr'] = $articleByYearnr;
			$articleByMonth ['articles_month_nr'] = $articleByMonthnr;
			$articleByMonth ['articles_month_name'] = imreporting_getMonthNameById ( $articleByMonthnr );
			$articleByMonth ['articles_year_name'] = $articleByYearname;
			$ret [] = $articleByMonth;
		}
		return $ret;
	}
	
	function getArticlesCountByCategories(){
		$categories = $this->getCategoriesArray();
		$ret = array();
		foreach ($categories as $cid=>$title){
			$qtde = $this->getArticlesCount(false,$cid);
			$cat = array('title'=>$title,'qtde'=>$qtde);
			$ret[$cid] = $cat;
		}
		
		return $ret;
	}
	
	/**
	 * Get Articles requested by the global search feature
	 *
	 * @param array $queryarray array containing the searched keywords
	 * @param bool $andor wether the keywords should be searched with AND or OR
	 * @param int $limit maximum results returned
	 * @param int $offset where to start in the resulting dataset
	 * @param int $userid should we return articles by specific articleer ?
	 * @return array array of articles
	 */
	function getArticlesForSearch($queryarray, $andor, $limit, $offset, $userid) {
		$criteria = new CriteriaCompo ( );
		
		$criteria->setStart ( $offset );
		$criteria->setLimit ( $limit );
		
		if ($userid != 0) {
			$criteria->add ( new Criteria ( 'article_uid', $userid ) );
		}
		if ($queryarray) {
			$criteriaKeywords = new CriteriaCompo ( );
			for($i = 0; $i < count ( $queryarray ); $i ++) {
				$criteriaKeyword = new CriteriaCompo ( );
				$criteriaKeyword->add ( new Criteria ( 'article_title', '%' . $queryarray [$i] . '%', 'LIKE' ), 'OR' );
				$criteriaKeyword->add ( new Criteria ( 'article_content', '%' . $queryarray [$i] . '%', 'LIKE' ), 'OR' );
				$criteriaKeywords->add ( $criteriaKeyword, $andor );
				unset ( $criteriaKeyword );
			}
			$criteria->add ( $criteriaKeywords );
		}
		$criteria->add ( new Criteria ( 'article_status', IMREPORTING_ARTICLE_STATUS_PUBLISHED ) );
		return $this->getObjects ( $criteria, true, false );
	}
	
	/**
	 * Update number of comments on a article
	 *
	 * This method is triggered by imreporting_com_update in include/functions.php which is
	 * called by ImpressCMS when updating comments
	 *
	 * @param int $article_id id of the article to update
	 * @param int $total_num total number of comments so far in this article
	 * @return VOID
	 */
	function updateComments($article_id, $total_num) {
		$articleObj = $this->get ( $article_id );
		if ($articleObj && ! $articleObj->isNew ()) {
			$articleObj->setVar ( 'article_comments', $total_num );
			$this->insert ( $articleObj, true );
		}
	}
	
	/**
	 * Check wether the current user can submit a new article or not
	 *
	 * @return bool true if he can false if not
	 */
	function userCanSubmit() {
		global $xoopsUser, $imreporting_isAdmin;
		$imreportingModuleConfig = icms_getModuleConfig ( 'imreporting' );
		
		if (! is_object ( $xoopsUser )) {
			return false;
		}
		if ($imreporting_isAdmin) {
			return true;
		}
		$user_groups = $xoopsUser->getGroups ();
		return count ( array_intersect ( $imreportingModuleConfig ['poster_groups'], $user_groups ) ) > 0;
	}
	
	/**
	 * BeforeSave event
	 *
	 * Event automatically triggered by IcmsPersistable Framework before the object is inserted or updated.
	 *
	 * @param object $obj ImreportingArticle object
	 * @return true
	 */
	function beforeSave(& $obj) {
		$pubdate = $obj->getVar ( 'article_published_date', 'n' );
		$expdate = $obj->getVar ( 'article_expired_date', 'n' );
		if ($obj->getVar ( 'article_status', 'n' ) == IMREPORTING_ARTICLE_STATUS_PUBLISHED && $expdate != $pubdate && $expdate <= time ()) {
			$obj->setVar ( 'article_status', IMREPORTING_ARTICLE_STATUS_EXPIRED );
		}
		if ($obj->updating_counter)
			return true;
		
		$obj->setVar ( 'dobr', $obj->need_do_br () );
		
		return true;
	}
	
	/**
	 * AfterSave event
	 *
	 * Event automatically triggered by IcmsPersistable Framework after the object is inserted or updated
	 *
	 * @param object $obj ImreportingArticle object
	 * @return true
	 */
	function afterSave(& $obj) {
		if ($obj->updating_counter)
			return true;
			
		// storing categories
		$imtagging_category_link_handler = xoops_getModuleHandler ( 'category_link', 'imtagging' );
		$imtagging_category_link_handler->storeCategoriesForObject ( $obj );
		
		if (! $obj->getVar ( 'article_notification_sent' ) && $obj->getVar ( 'article_status', 'e' ) == IMREPORTING_ARTICLE_STATUS_PUBLISHED) {
			$obj->sendNotifArticlePublished ();
			$obj->setVar ( 'article_notification_sent', true );
			$this->insert ( $obj );
		}
		return true;
	}
	
	/**
	 * Update the counter field of the article object
	 *
	 * @param int $article_id
	 *
	 * @return VOID
	 */
	function updateCounter($id) {
		global $xoopsUser, $imreporting_isAdmin;
		
		$articleObj = $this->get ( $id );
		if (! is_object ( $articleObj )) {
			return false;
		}
		if (!is_object($xoopsUser) || (!$imreporting_isAdmin && $articleObj->getVar ( 'article_uid', 'e' ) != $xoopsUser->uid ())) {
			$articleObj->updating_counter = true;
			$articleObj->setVar ( 'counter', $articleObj->getVar ( 'counter', 'n' ) + 1 );
			$this->insert ( $articleObj, true );
		}
		
		return true;
	}
}
?>