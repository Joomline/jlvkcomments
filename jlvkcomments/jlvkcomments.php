<?php
/**
 * Jlvkcomments
 *
 * @version 2.0.0
 * @author Vadim Kunicin(vadim@joomline.ru), Anton Voynov (anton@joomline.ru)
 * @copyright (C) 2010-2022 by Anton Voynov(http://www.joomline.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');


class plgContentJlvkcomments extends JPlugin
{

	public function onContentPrepare($context, &$article, &$params, $page = 0){
	if($context == 'com_content.article'){

		JPlugin::loadLanguage( 'plg_content_Jlvkcomments' );	



		if (strpos($article->text, '{jlvkcomments-off}') !== false) {
			$article->text = str_replace("{jlvkcomments-off}","",$article->text);
			return true;
		}

		if (strpos($article->text, '{jlvkcomments}') === false && !$this->params->def('autoAdd')) {
			return true;
		}

		$exceptcat = is_array($this->params->def('categories')) ? $this->params->def('categories') : array($this->params->def('categories'));
		if (!in_array($article->catid,$exceptcat)) {
			$view = JFactory::getApplication()->input->get('view');
			if ($view == 'article') {

				$doc = &JFactory::getDocument();
				
				$apiId 			= $this->params->def('apiId');
				$width 			= $this->params->def('width');
				$comLimit 		= $this->params->def('comLimit');
				$attach 		= $this->params->def('attach');
				$autoPublish 	= $this->params->def('autoPublish');
				$norealtime 	= $this->params->def('norealtime');
				$script 		= "VK.init({apiId: $apiId, onlyWidgets: true});";				
				$doc->addScript('//vk.com/js/api/openapi.js?169');
				$doc->addScriptDeclaration($script);

				$pagehash = $article->id;
				$scriptPage .= <<<HTML
				
					<div id='jlvkcomments'></div>
					<script type='text/javascript'>
					VK.Widgets.Comments('jlvkcomments', {limit: $comLimit, width: '$width', attach: '$attach', autoPublish: $autoPublish, norealtime: $norealtime},$pagehash);
					</script>
					
					
				
					
HTML;
				if (in_array($pagehash, array(5,16,31,47,74,99,145,288,447,897,1363,2461))){
					$scriptPage .= <<<HTML
					<div style="text-align: right;">
						<a style="text-decoration:none; color: #c0c0c0; font-family: arial,helvetica,sans-serif; font-size: 5pt; " target="_blank" href="https://joomline.net">https://joomline.net</a>
					</div>
					
HTML;
				}
				
				if ($this->params->def('autoAdd') == 1) {
					$article->text .= $scriptPage;
				} else {
					$article->text = str_replace("{jlvkcomments}",$scriptPage,$article->text);
				}

			}
		} else {
			$article->text = str_replace("{jlvkcomments}","",$article->text);
		}

	}
}

}
