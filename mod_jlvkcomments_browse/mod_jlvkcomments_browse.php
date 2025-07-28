<?php
/**
 * JoomLine VK Comments Browse Module
 *
 * @version 1.0.0
 * @author Joomline (sale@joomline.ru)
 * @copyright (C) 2010-2025 by Joomline(http://www.joomline.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\WebAsset\WebAssetManager;



// Get module parameters
$apiId = $params->get('apiId');
$width = $params->get('width', 500);
$height = $params->get('height', 0);
$limit = $params->get('limit', 10);
$mini = $params->get('mini', 'auto');
$norealtime = $params->get('norealtime', 0);
$hideLink = $params->get('hideLink', 0);

// Add VK API script using WebAssetManager
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->registerAndUseScript('vk-api-browse', 'https://vk.com/js/api/openapi.js?169', [], []);

// Generate widget HTML
$widgetHtml = '<div id="vk_comments_browse"></div>';

// Add link if not hidden
if (!$hideLink) {
    $widgetHtml .= '<div class="jlvkcomments-link" style="text-align: right; margin-top: 10px; font-size: 10px;">';
    $widgetHtml .= '<a href="https://joomline.ru/rasshirenija/plugin/plugin-jl-vkcomments.html" target="_blank" style="color: #999; text-decoration: none;">Расширения ВК</a>';
    $widgetHtml .= '</div>';
}

$widgetHtml .= '<script type="text/javascript">';
$widgetHtml .= 'function initVKCommentsBrowse() {';
$widgetHtml .= 'if (typeof VK !== "undefined") {';
$widgetHtml .= 'VK.init({apiId: ' . (int)$apiId . ', onlyWidgets: true});';
$widgetHtml .= 'VK.Widgets.CommentsBrowse("vk_comments_browse", {';
$widgetHtml .= 'width: ' . (int)$width . ', ';
if ($height > 0) {
    $widgetHtml .= 'height: ' . (int)$height . ', ';
}
$widgetHtml .= 'limit: ' . (int)$limit . ', ';
if ($mini !== 'auto') {
    $widgetHtml .= 'mini: ' . (int)$mini . ', ';
}
$widgetHtml .= 'norealtime: ' . (int)$norealtime;
$widgetHtml .= '});';
$widgetHtml .= '} else {';
$widgetHtml .= 'setTimeout(initVKCommentsBrowse, 500);';
$widgetHtml .= '}';
$widgetHtml .= '}';
$widgetHtml .= 'document.addEventListener("DOMContentLoaded", initVKCommentsBrowse);';
$widgetHtml .= '</script>';

require ModuleHelper::getLayoutPath('mod_jlvkcomments_browse', 'default'); 