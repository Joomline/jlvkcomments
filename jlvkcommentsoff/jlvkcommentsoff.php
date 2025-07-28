<?php
/**
 * Jlvkcommentsoff plugin
 *
 * @version 2.0.0
 * @author Anton Voynov (anton@joomline.ru)
 * @copyright (C) 2010-2024 by Anton Voynov(http://www.joomline.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Factory;
use Joomla\CMS\Object\CMSObject;

/**
 * Jlvkcommentsoff plugin
 *
 * @since  2.0.0
 */
class PlgButtonJlvkcommentsoff extends CMSPlugin
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
			function insertJlvkcommentsoff(editor) {
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
				
				if (content && content.match(/{jlvkcomments-off}/)) {
					return false;
				} else {
					jInsertEditorText('{jlvkcomments-off}', editor);
				}
			}
		";

		$doc->addScriptDeclaration($js);

		$button = new CMSObject;
		$button->set('modal', false);
		$button->set('onclick', 'insertJlvkcommentsoff(\'' . $name . '\');return false;');
		$button->set('text', "Выкл JLVKComments");
		$button->set('name', 'butttonjlvkcommentsoff');
		$button->set('link', '#');

		return $button;
	}
}