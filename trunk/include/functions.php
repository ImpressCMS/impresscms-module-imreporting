<?php
/**
 * Common functions used by the module
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

/**
 * Get module admion link
 *
 * @todo to be move in icms core
 *
 * @param string $moduleName dirname of the moodule
 * @return string URL of the admin side of the module
 */

function imreporting_getModuleAdminLink($moduleName = 'imreporting') {
	global $xoopsModule;
	if (! $moduleName && (isset ( $xoopsModule ) && is_object ( $xoopsModule ))) {
		$moduleName = $xoopsModule->getVar ( 'dirname' );
	}
	$ret = '';
	if ($moduleName) {
		$ret = "<a href='" . ICMS_URL . "/modules/$moduleName/admin/index.php'>" . _MD_IMREPORTING_ADMIN_PAGE . "</a>";
	}
	return $ret;
}

/**
 * @todo to be move in icms core
 */
function imreporting_getModuleName($withLink = true, $forBreadCrumb = false, $moduleName = false) {
	if (! $moduleName) {
		global $xoopsModule;
		$moduleName = $xoopsModule->getVar ( 'dirname' );
	}
	$icmsModule = icms_getModuleInfo ( $moduleName );
	$icmsModuleConfig = icms_getModuleConfig ( $moduleName );
	if (! isset ( $icmsModule )) {
		return '';
	}
	
	if (! $withLink) {
		return $icmsModule->getVar ( 'name' );
	} else {
		/*	    $seoMode = smart_getModuleModeSEO($moduleName);
	    if ($seoMode == 'rewrite') {
	    	$seoModuleName = smart_getModuleNameForSEO($moduleName);
	    	$ret = XOOPS_URL . '/' . $seoModuleName . '/';
	    } elseif ($seoMode == 'pathinfo') {
	    	$ret = XOOPS_URL . '/modules/' . $moduleName . '/seo.php/' . $seoModuleName . '/';
	    } else {
			$ret = XOOPS_URL . '/modules/' . $moduleName . '/';
	    }
*/
		$ret = ICMS_URL . '/modules/' . $moduleName . '/';
		return '<a href="' . $ret . '">' . $icmsModule->getVar ( 'name' ) . '</a>';
	}
}

/**
 * Get URL of previous page
 *
 * @todo to be moved in ImpressCMS 1.2 core
 *
 * @param string $default default page if previous page is not found
 * @return string previous page URL
 */
function imreporting_getPreviousPage($default = false) {
	global $impresscms;
	if (isset ( $impresscms->urls ['previouspage'] )) {
		return $impresscms->urls ['previouspage'];
	} elseif ($default) {
		return $default;
	} else {
		return ICMS_URL;
	}
}

/**
 * Get month name by its ID
 *
 * @todo to be moved in ImpressCMS 1.2 core
 *
 * @param int $month_id ID of the month
 * @return string month name
 */
function imreporting_getMonthNameById($month_id) {
	return Icms_getMonthNameById ( $month_id );
}

/**
 * Return a linked username or full name for a specific $userid
 *
 * @todo this function is fixing a ucwords bug in icms_getLinkedUnameFromId so we will update this in icms 1.2
 *
 * @param integer $userid uid of the related user
 * @param bool $name true to return the fullname, false to use the username; if true and the user does not have fullname, username will be used instead
 * @param array $users array already containing XoopsUser objects in which case we will save a query
 * @param bool $withContact true if we want contact details to be added in the value returned (PM and email links)
 * @return string name of user with a link on his profile
 */
function imreporting_getLinkedUnameFromId($userid, $name = false, $users = array (), $withContact = false) {
	if (! is_numeric ( $userid )) {
		return $userid;
	}
	$userid = intval ( $userid );
	if ($userid > 0) {
		if ($users == array ( )) {
			//fetching users
			$member_handler = & xoops_gethandler ( 'member' );
			$user = & $member_handler->getUser ( $userid );
		} else {
			if (! isset ( $users [$userid] )) {
				return $GLOBALS ['xoopsConfig'] ['anonymous'];
			}
			$user = & $users [$userid];
		}
		if (is_object ( $user )) {
			$ts = & MyTextSanitizer::getInstance ();
			$username = $user->getVar ( 'uname' );
			$fullname = '';
			$fullname2 = $user->getVar ( 'name' );
			if (($name) && ! empty ( $fullname2 )) {
				$fullname = $user->getVar ( 'name' );
			}
			if (! empty ( $fullname )) {
				$linkeduser = "$fullname [<a href='" . ICMS_URL . "/userinfo.php?uid=" . $userid . "'>" . $ts->htmlSpecialChars ( $username ) . "</a>]";
			} else {
				$linkeduser = "<a href='" . ICMS_URL . "/userinfo.php?uid=" . $userid . "'>" . $ts->htmlSpecialChars ( $username ) . "</a>";
			}
			// add contact info : email + PM
			if ($withContact) {
				$linkeduser .= '<a href="mailto:' . $user->getVar ( 'email' ) . '"><img style="vertical-align: middle;" src="' . ICMS_URL . '/images/icons/email.gif' . '" alt="' . _US_SEND_MAIL . '" title="' . _US_SEND_MAIL . '"/></a>';
				$js = "javascript:openWithSelfMain('" . ICMS_URL . '/pmlite.php?send2=1&to_userid=' . $userid . "', 'pmlite',450,370);";
				$linkeduser .= '<a href="' . $js . '"><img style="vertical-align: middle;" src="' . ICMS_URL . '/images/icons/pm.gif' . '" alt="' . _US_SEND_PM . '" title="' . _US_SEND_PM . '"/></a>';
			}
			return $linkeduser;
		}
	}
	return $GLOBALS ['xoopsConfig'] ['anonymous'];
}

if (! function_exists ( 'Icms_getMonthNameById' )) {
	/**
	 * Get month name by its ID
	 *
	 * @param int $month_id ID of the month
	 * @return string month name
	 */
	
	function Icms_getMonthNameById($month_id) {
		global $xoopsConfig;
		icms_loadLanguageFile ( 'core', 'calendar' );
		$month_id = icms_conv_local2nr ( $month_id );
		if ($xoopsConfig ['use_ext_date'] == 1 && defined ( '_CALENDAR_TYPE' ) && _CALENDAR_TYPE == "jalali") {
			switch ( $month_id) {
				case 1 :
					return _CAL_FARVARDIN;
				break;
				case 2 :
					return _CAL_ORDIBEHESHT;
				break;
				case 3 :
					return _CAL_KHORDAD;
				break;
				case 4 :
					return _CAL_TIR;
				break;
				case 5 :
					return _CAL_MORDAD;
				break;
				case 6 :
					return _CAL_SHAHRIVAR;
				break;
				case 7 :
					return _CAL_MEHR;
				break;
				case 8 :
					return _CAL_ABAN;
				break;
				case 9 :
					return _CAL_AZAR;
				break;
				case 10 :
					return _CAL_DEY;
				break;
				case 11 :
					return _CAL_BAHMAN;
				break;
				case 12 :
					return _CAL_ESFAND;
				break;
			}
		} else {
			switch ( $month_id) {
				case 1 :
					return _CAL_JANUARY;
				break;
				case 2 :
					return _CAL_FEBRUARY;
				break;
				case 3 :
					return _CAL_MARCH;
				break;
				case 4 :
					return _CAL_APRIL;
				break;
				case 5 :
					return _CAL_MAY;
				break;
				case 6 :
					return _CAL_JUNE;
				break;
				case 7 :
					return _CAL_JULY;
				break;
				case 8 :
					return _CAL_AUGUST;
				break;
				case 9 :
					return _CAL_SEPTEMBER;
				break;
				case 10 :
					return _CAL_OCTOBER;
				break;
				case 11 :
					return _CAL_NOVEMBER;
				break;
				case 12 :
					return _CAL_DECEMBER;
				break;
			}
		}
	}
}
if (! function_exists ( 'icms_conv_local2nr' )) {
	function icms_conv_local2nr($string) {
		$basecheck = defined ( '_USE_LOCAL_NUM' ) && _USE_LOCAL_NUM;
		if ($basecheck) {
			$string = str_replace ( array (_LCL_NUM0, _LCL_NUM1, _LCL_NUM2, _LCL_NUM3, _LCL_NUM4, _LCL_NUM5, _LCL_NUM6, _LCL_NUM7, _LCL_NUM8, _LCL_NUM9 ), array ('0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ), $string );
		}
		return $string;
	}
}
?>