<?php
namespace Zf2TranslationScanner\File\Source\Scanner;

use Zf2TranslationScanner\File\Source\ScannerAbstract;

class Xml extends ScannerAbstract
{
    /**
     * @param string $content
     * @return array
     */
    public function parse($content)
    {
        $matches = array_merge($this->_scanForConstruction('/\<description\>([^\<]+)\<\/description\>/i', $content),
            $this->_scanForConstruction('/\<label\>([^\<]+)\<\/label\>/i', $content),
            $this->_scanForConstruction('/\<title\>([^\<]+)\<\/title\>/i', $content)
        );
        return $matches;
    }
}