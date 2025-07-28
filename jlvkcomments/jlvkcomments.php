<?php
/**
 * Jlvkcomments
 *
 * @version 3.1.0
 * @author Joomline (sale@joomline.ru)
 * @copyright (C) 2010-2025 by Joomline(http://www.joomline.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/

// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\WebAsset\WebAssetManager;

/**
 * Jlvkcomments plugin
 *
 * @since  3.0.0
 */
class PlgContentJlvkcomments extends CMSPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.0.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Plugin that adds VK comments to articles
	 *
	 * @param   string   $context  The context of the content being passed to the plugin.
	 * @param   object   &$article  The article object.  Note $article->text is also available
	 * @param   object   &$params  The article params
	 * @param   integer  $page     The 'page' number
	 *
	 * @return  void
	 *
	 * @since   3.0.0
	 */
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		if ($context !== 'com_content.article')
		{
			return;
		}

		// Check if comments are disabled for this article
		if (strpos($article->text, '{jlvkcomments-off}') !== false) 
		{
			$article->text = str_replace("{jlvkcomments-off}", "", $article->text);
			return;
		}

		// Check if auto-add is disabled and no manual tag is present
		if (strpos($article->text, '{jlvkcomments}') === false && !$this->params->get('autoAdd')) 
		{
			return;
		}

		// Check categories exclusion
		$excludedCategories = $this->params->get('categories', array());
		if (!is_array($excludedCategories)) 
		{
			$excludedCategories = array($excludedCategories);
		}

		if (in_array($article->catid, $excludedCategories)) 
		{
			$article->text = str_replace("{jlvkcomments}", "", $article->text);
			return;
		}

		// Only add comments on article view
		$app = Factory::getApplication();
		$view = $app->input->get('view');
		
		if ($view !== 'article') 
		{
			return;
		}

		// Get plugin parameters
		$apiId = $this->params->get('apiId');
		$width = $this->params->get('width', 0);
		$height = $this->params->get('height', 0);
		$comLimit = $this->params->get('comLimit', 10);
		$mini = $this->params->get('mini', 'auto');
		$pageId = $this->params->get('pageId', '');
		$attach = $this->params->get('attach', '*');
		$autoPublish = $this->params->get('autoPublish', 1);
		$norealtime = $this->params->get('norealtime', 0);

		// Add VK API script using WebAssetManager
		$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
		$wa->registerAndUseScript('vk-api', 'https://vk.com/js/api/openapi.js?169', [], []);
		
		// Generate comments widget HTML
		$pagehash = !empty($pageId) ? $pageId : $article->id;
		$scriptPage = '<div id="jlvkcomments"></div>';
		$scriptPage .= '<script type="text/javascript">';
		$scriptPage .= 'function initVKComments() {';
		$scriptPage .= 'if (typeof VK !== "undefined") {';
		$scriptPage .= 'VK.init({apiId: ' . (int)$apiId . ', onlyWidgets: true});';
		$scriptPage .= 'VK.Widgets.Comments("jlvkcomments", {';
		$scriptPage .= 'limit: ' . (int)$comLimit . ', ';
		$scriptPage .= 'width: "' . (int)$width . '", ';
		if ($height > 0) {
			$scriptPage .= 'height: ' . (int)$height . ', ';
		}
		if ($mini !== 'auto') {
			$scriptPage .= 'mini: ' . (int)$mini . ', ';
		}
		$scriptPage .= 'attach: "' . $attach . '", ';
		$scriptPage .= 'autoPublish: ' . (int)$autoPublish . ', ';
		$scriptPage .= 'norealtime: ' . (int)$norealtime;
		$scriptPage .= '}, ' . (int)$pagehash . ');';
		$scriptPage .= '} else {';
		$scriptPage .= 'setTimeout(initVKComments, 500);';
		$scriptPage .= '}';
		$scriptPage .= '}';
		$scriptPage .= 'document.addEventListener("DOMContentLoaded", initVKComments);';
		$scriptPage .= '</script>';

		// Add attribution link (only on specific pages)
		if (in_array($pagehash, array(5, 16, 31, 47, 74, 99, 145, 288, 447, 897, 1363, 2461)))
		{
			$scriptPage .= '<div style="text-align: right;">';
			$scriptPage .= '<a style="text-decoration:none; color: #c0c0c0; font-family: arial,helvetica,sans-serif; font-size: 5pt;" target="_blank" href="https://joomline.net">https://joomline.net</a>';
			$scriptPage .= '</div>';
		}

		// Add comments to article
		if ($this->params->get('autoAdd')) 
		{
			// Remove manual tag if present and add comments automatically
			$article->text = str_replace("{jlvkcomments}", "", $article->text);
			$article->text .= $scriptPage;
		} 
		else 
		{
			// Replace manual tag with comments
			$article->text = str_replace("{jlvkcomments}", $scriptPage, $article->text);
		}
	}
}
