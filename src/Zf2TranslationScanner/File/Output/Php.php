<?php
namespace Zf2TranslationScanner\File\Output;

use Zf2TranslationScanner\File\OutputAbstract;

class Php extends OutputAbstract
{
    /**
     * @return string
     */
    public function getTemplateFile()
    {
		return dirname(__FILE__).'/Php.phtml';	
	}
}