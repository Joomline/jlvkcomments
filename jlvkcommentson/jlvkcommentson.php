<?php
/**
 * Jlvkcommentson plugin
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

		$getContent = $this->_subject->getContent($name);

		$js = "
			function insertJlvkcommentson(editor) {
				var content = $getContent
				if (content.match(/{jlvkcomments}/)) {
					return false;
				} else {
					jInsertEditorText('{jlvkcomments}', editor);
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

		return $button;
	}
}