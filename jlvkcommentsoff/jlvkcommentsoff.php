<?php
// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgButtonJlvkcommentsoff extends JPlugin
{
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	public function onDisplay($name)
	{
		$app 		= JFactory::getApplication();

		$doc		= JFactory::getDocument();
		$template	= $app->getTemplate();



		$getContent = $this->_subject->getContent($name);

		$js = "
			function insertJlvkcommentsoff(editor) {
				var content = $getContent
				if (content.match(/{jlvkcomments-off}/)) {
					return false;
				} else {
					jInsertEditorText('{jlvkcomments-off}', editor);
				}
			}
		";


		$doc->addScriptDeclaration($js);

		$button = new JObject;
		$button->set('modal', false);
		$button->set('onclick', 'insertJlvkcommentsoff(\''.$name.'\');return false;');
		$button->set('text', "Выкл JLVKComments");
		$button->set('name', 'butttonjlvkcommentsoff');


		$button->set('link', '#');

		return $button;

	}
}