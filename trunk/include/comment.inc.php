<?php
/**
* Comment include file
*
* File holding functions used by the module to hook with the comment system of ImpressCMS
*
* @copyright	
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Rodrigo Lima aka TheRplima <therplima@impresscms.org>
* @package		imreporting
* @version		$Id$
*/

function imreporting_com_update($item_id, $total_num)
{
    $imreporting_article_handler = xoops_getModuleHandler('article', 'imreporting');
    $imreporting_article_handler->updateComments($item_id, $total_num);
}

function imreporting_com_approve(&$comment)
{
    // notification mail here
}

?>