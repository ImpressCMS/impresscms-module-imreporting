<?php
/**
* Portuguesebr language constants commonly used in the module
*
* @copyright	
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Rodrigo Lima aka TheRplima <therplima@impresscms.org>
* @package		imreporting
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path não está definido");

// article
define("_CO_IMREPORTING_ARTICLE_CATEGORIES", "Categorias");
define("_CO_IMREPORTING_ARTICLE_CATEGORIES_DSC", "Selecione as categorias para a qual você gostaria de vincular este artigo");
define("_CO_IMREPORTING_ARTICLE_ARTICLE_TITLE", "Título");
define("_CO_IMREPORTING_ARTICLE_ARTICLE_TITLE_DSC", " ");
define("_CO_IMREPORTING_ARTICLE_ARTICLE_UID", "Cadastrador");
define("_CO_IMREPORTING_ARTICLE_ARTICLE_UID_DSC", " ");
define("_CO_IMREPORTING_ARTICLE_ARTICLE_CID", "Categoria");
define("_CO_IMREPORTING_ARTICLE_ARTICLE_CID_DSC", " ");
define("_CO_IMREPORTING_ARTICLE_ARTICLE_SUMMARY", "Resumo");
define("_CO_IMREPORTING_ARTICLE_ARTICLE_SUMMARY_DSC", " ");
define("_CO_IMREPORTING_ARTICLE_ARTICLE_CONTENT", "Conteúdo");
define("_CO_IMREPORTING_ARTICLE_ARTICLE_CONTENT_DSC", " ");
define("_CO_IMREPORTING_ARTICLE_ARTICLE_PUBLISHED_DATE", "Data de Publicação");
define("_CO_IMREPORTING_ARTICLE_ARTICLE_PUBLISHED_DATE_DSC", " ");
define("_CO_IMREPORTING_ARTICLE_ARTICLE_EXPIRED_DATE", "Data para Expirar");
define("_CO_IMREPORTING_ARTICLE_ARTICLE_EXPIRED_DATE_DSC", "Caso queira que o artigo expire automaticamente e pare de ser exibido no site defina uma data para que isso ocorra. Caso a data seja a mesma da publicação o artigo permanecerá sendo exibido no site até que alguém o remova.");
define("_CO_IMREPORTING_ARTICLE_ARTICLE_STATUS", "Status");
define("_CO_IMREPORTING_ARTICLE_ARTICLE_STATUS_DSC", " ");
define("_CO_IMREPORTING_ARTICLE_ARTICLE_CANCOMMENT", "Pode receber comentários?");
define("_CO_IMREPORTING_ARTICLE_ARTICLE_CANCOMMENT_DSC", "");
define("_CO_IMREPORTING_ARTICLE_ARTICLE_INCLUDE_SUMMARY", "Incluir Resumo?");
define("_CO_IMREPORTING_ARTICLE_ARTICLE_INCLUDE_SUMMARY_DSC", "Marque <b>SIM</b> para exibir o resumo na página do artigo completo.");

define("_CO_IMREPORTING_ARTICLE_INFO", "Publicado por %s em %s.");
define("_CO_IMREPORTING_ARTICLE_FROM_USER", "Todos os artigos de %s");
define("_CO_IMREPORTING_ARTICLE_FROM_MONTH", "Todos os artigos publicados em %s %u");
define("_CO_IMREPORTING_ARTICLE_COMMENTS_INFO", "%d comentários");
define("_CO_IMREPORTING_ARTICLE_NO_COMMENT", "Sem comentário");

// article status
define("_CO_IMREPORTING_ARTICLE_STATUS_PUBLISHED", "Publicado");
define("_CO_IMREPORTING_ARTICLE_STATUS_PENDING", "Necessita Revisã");
define("_CO_IMREPORTING_ARTICLE_STATUS_DRAFT", "Rascunho");
define("_CO_IMREPORTING_ARTICLE_STATUS_PRIVATE", "Privado");
define("_CO_IMREPORTING_ARTICLE_STATUS_EXPIRED", "Expirado");

// common language
define("_CO_IMREPORTING_ARTICLE_PUBLISHED", "Publicado");
define("_CO_IMREPORTING_ARTICLE_PENDING", "Enviado para revisã");
define("_CO_IMREPORTING_ARTICLE_DRAFT", "Enviado como rascunho");
define("_CO_IMREPORTING_ARTICLE_PRIVATE", "Publicado como privado");
define("_CO_IMREPORTING_ARTICLE_EXPIRED", "Expirado");
define("_CO_IMREPORTING_ARTICLE_ON", "em");
define("_CO_IMREPORTING_ARTICLE_BY", "por");
define("_CO_IMREPORTING_FILED_UNDER", "Arquivado sob: ");
?>