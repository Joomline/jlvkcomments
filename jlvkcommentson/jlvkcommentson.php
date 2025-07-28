<?php
/**
 * Jlvkcommentson plugin
 *
 * @version 2.0.0
 * @author Joomline (sale@joomline.ru)
 * @copyright (C) 2010-2025 by Joomline(http://www.joomline.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Factory;
use Joomla\CMS\Object\CMSObject;

/**
 * Jlvkcommentson plugin
 *
 * @since  2.0.0
 */
class PlgButtonJlvkcommentson extends CMSPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  2.0.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Display the button
	 *
	 * @param   string  $name  The name of the button to add
	 *
	 * @return  CMSObject  The button options as CMSObject
	 *
	 * @since   2.0.0
	 */
	public function onDisplay($name)
	{
		$app = Factory::getApplication();
		$doc = Factory::getDocument();

		$js = "
			function insertJlvkcommentson(editor) {
				var content = '';
				if (typeof Joomla !== 'undefined' && Joomla.editors && Joomla.editors.instances && Joomla.editors.instances[editor]) {
					content = Joomla.editors.instances[editor].getValue();
				} else if (typeof tinyMCE !== 'undefined' && tinyMCE.get(editor)) {
					content = tinyMCE.get(editor).getContent();
				} else if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances[editor]) {
					content = CKEDITOR.instances[editor].getData();
				} else {
					var textarea = document.getElementById(editor);
					if (textarea) {
						content = textarea.value;
					}
				}
				
				if (content && content.match(/{jlvkcomments}/)) {
					return false;
				} else {
					// Modern Joomla 5 editor API
					if (typeof Joomla !== 'undefined' && Joomla.editors && Joomla.editors.instances && Joomla.editors.instances[editor]) {
						var currentContent = Joomla.editors.instances[editor].getValue();
						Joomla.editors.instances[editor].setValue(currentContent + '{jlvkcomments}');
					} else if (typeof tinyMCE !== 'undefined' && tinyMCE.get(editor)) {
						var currentContent = tinyMCE.get(editor).getContent();
						tinyMCE.get(editor).setContent(currentContent + '{jlvkcomments}');
					} else if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances[editor]) {
						var currentContent = CKEDITOR.instances[editor].getData();
						CKEDITOR.instances[editor].setData(currentContent + '{jlvkcomments}');
					} else {
						// Fallback for regular textarea
						var textarea = document.getElementById(editor);
						if (textarea) {
							textarea.value += '{jlvkcomments}';
							// Trigger change event
							var event = new Event('input', { bubbles: true });
							textarea.dispatchEvent(event);
						}
					}
				}
			}
		";

		$doc->addScriptDeclaration($js);

		$button = new CMSObject;
		$button->set('modal', false);
		$button->set('onclick', 'insertJlvkcommentson(\'' . $name . '\');return false;');
		$button->set('text', "Вкл JLVKComments");
		$button->set('name', 'butttonjlvkcommentson');
		$button->set('link', '#');
		$button->set('icon', 'fas fa-comments');

		return $button;
	}
}