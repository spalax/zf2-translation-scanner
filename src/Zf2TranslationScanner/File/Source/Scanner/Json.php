<?php
namespace Zf2TranslationScanner\File\Source\Scanner;

use Zf2TranslationScanner\File\Source\ScannerAbstract;

class Json extends ScannerAbstract
{
	/**
	 * @param   string $content
	 * @return   array
	 */
	public function parse($content)
    {
        $matches = array_merge($this->_scanForConstruction('/[\"|\']{1}title[\"|\']{1}\s*\:\s*[\"|\']{1}(.+?)[\"|\']{1}/i',
                                                           $content));
        return $matches;
	}
}