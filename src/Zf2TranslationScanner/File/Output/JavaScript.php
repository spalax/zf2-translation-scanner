<?php
namespace Zf2TranslationScanner\File\Output;

use Zf2TranslationScanner\File\OutputAbstract;

class JavaScript extends OutputAbstract
{
	/**
	 * @return string
	 */
	public function getTemplateFile()
    {
		return dirname(__FILE__).'/JavaScript.phtml';	
	}
}