<?php
namespace Zf2TranslationScanner\File\Source\Scanner;

use Zf2TranslationScanner\File\Source\ScannerAbstract;

class Phtml extends ScannerAbstract
{

    /**
     * Parse phtml files for search translatable
     * constructions
     *
     * @param   string $content
     * @return   array
     * @see  Scanner::parse()
     * @see  ScannerAbstract::parse()
     */
    public function parse($content)
    {
        $foundStrings = array_merge(
            $this->_scanForConstruction('/translate\(\'([^\']+)\'.*?\)/smx', $content),
            $this->_scanForConstruction('/translate\(\"([^\"]+)\".*?\)/smx', $content)
        );
        return $foundStrings;
    }
}
