<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2015 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('resticted aceess');

require_once ( JPATH_COMPONENT .'/builder/builder_layout.php' );

jimport( 'joomla.filesystem.file' );
$input  			= JFactory::getApplication()->input;

if ($input->get('import') === 'local') {
	$file 				= $input->files->get('importLayout');

	if($file) {
		$content = JFile::read($file['tmp_name']);
		echo dataLayoutBuilder(json_decode( $content ));
	}
	else
	{
		echo '<h1>There is no such template</h1>';
	}
	die();
}
elseif ($input->get('import') === 'copy')
{
	$copy_text = $_POST['copyText'];
	$decode_text = $copy_text;

	echo base64_encode($decode_text);
	die();
}
elseif ($input->get('import') === 'paste')
{
	$copy_text 		= $_POST['pasteText'];
	$decode_text 	= $copy_text;

	echo base64_decode($decode_text);
	die();
}
else
{
	$template_name = $_POST['template'];
	$path = JPATH_COMPONENT.'/builder/templates/'.$template_name.'.json';
	if (JFile::exists($path))
	{
		$content = JFile::read($path);
		echo dataLayoutBuilder(json_decode( $content ));
	}
	else
	{
		echo '<h1>There is no such template</h1>';
	}
	die();
}