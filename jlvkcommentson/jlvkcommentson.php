<?php
// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgButtonJlvkcommentson extends JPlugin
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

		$button = new JObject;
		$button->set('modal', false);
		$button->set('onclick', 'insertJlvkcommentson(\''.$name.'\');return false;');
		$button->set('text', "Вкл JLVKComments");
		$button->set('name', 'butttonjlvkcommentson');


		$button->set('link', '#');

		return $button;

	}
}