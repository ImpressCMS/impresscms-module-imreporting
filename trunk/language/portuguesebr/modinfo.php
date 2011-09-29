<?php
/**
* Portuguesebr language constants related to module information
*
* @copyright	
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Rodrigo Lima aka TheRplima <therplima@impresscms.org>
* @package		imreporting
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path não está definido");

// Module Info
// The name of this module

global $xoopsModule;
define("_MI_IMREPORTING_MD_NAME", "imReporting");
define("_MI_IMREPORTING_MD_DESC", "Módulo simples de Notícias e Artigos para ImpressCMS");

define("_MI_IMREPORTING_ARTICLES", "Artigos");

// Configs
define("_MI_IMREPORTING_POSTERGR", "Grupos permitidos a enviar artigos");
define("_MI_IMREPORTING_POSTERGRDSC", "Selecione os grupos que terão permissão de enviar artigos. Por favor note que um usuário que faz parte de um desses grupos será capaz de enviar artigos diretamente ao site caso a opção de auto publicação abaixo esteja marcada como SIM.");
define("_MI_IMREPORTING_LIMIT", "Limite de Artigos");
define("_MI_IMREPORTING_LIMITDSC", "Número de artigos que serão exibidos por página no lado do usuário.");
define("_MI_IMREPORTING_AUTOPUBLISH", "Auto Publicar Artigos?");
define("_MI_IMREPORTING_AUTOPUBLISHDSC", "Selecione <b>NÃO</b> para moderar os artigos enviados pelos usuários do site. Se selecionar <b>SIM</b> todos os artigos enviados pelos usuários irão ser publicados sem intervenção do administrador.");
define("_MI_IMREPORTING_SHOWBREADCRUMB", "Mostrar Navegação?");
define("_MI_IMREPORTING_SHOWBREADCRUMBDSC", "Selecione <b>SIM</b> para exibir um menu de navegação no topo de cada página do módulo.");
define("_MI_IMREPORTING_DATEFORMAT", "Formato das datas");
define("_MI_IMREPORTING_DATEFORMATDSC", "Por favor, consulte a documentação do PHP (<a href='http://br.php.net/manual/br/function.date.php' target='_blank'>http://br.php.net/manual/br/function.date.php</a>) para obter mais informações sobre como selecionar o formato. Nota, se você não digitar nada, então o formato de data padrão será utilizado.");

// Blocks
define("_MI_IMREPORTING_ARTICLERECENT", "Artigos Recentes");
define("_MI_IMREPORTING_ARTICLERECENTDSC", "Exibe os artigos mais recentes");
define("_MI_IMREPORTING_ARTICLEBYMONTH", "Artigos por mês");
define("_MI_IMREPORTING_ARTICLEBYMONTHDSC", "Exibe uma lista de meses nos quais os artigos foram publicados com as quantidades em cada mês.");
define("_MI_IMREPORTING_ARTICLEBYCATEGORY", "Artigos por Categoria");
define("_MI_IMREPORTING_ARTICLEBYCATEGORYDSC", "Exibe uma lista de categorias nas quais existem artigos e as quantidades em cada categoria");

// Notifications
define("_MI_IMREPORTING_GLOBAL_NOTIFY", "Todos os artigos");
define("_MI_IMREPORTING_GLOBAL_NOTIFY_DSC", "Notificações relacionadas com todos os artigos do site.");
define("_MI_IMREPORTING_GLOBAL_ARTICLE_PUBLISHED_NOTIFY", "Novo artigo publicado");
define("_MI_IMREPORTING_GLOBAL_ARTICLE_PUBLISHED_NOTIFY_CAP", "Notifique-me quando um novo artigo for publicado.");
define("_MI_IMREPORTING_GLOBAL_ARTICLE_PUBLISHED_NOTIFY_DSC", "Receber notificaćão quando um novo artigo for publicado.");
define("_MI_IMREPORTING_GLOBAL_ARTICLE_PUBLISHED_NOTIFY_SBJ", "[{X_SITENAME}] {X_MODULE} auto-notificação : Novo artigo publicado");

// Submit button
define("_MI_IMREPORTING_ARTICLE_ADD", "Adicionar novo artigo");
?>