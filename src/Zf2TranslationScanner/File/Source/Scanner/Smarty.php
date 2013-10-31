<?php
namespace Zf2TranslationScanner\File\Source\Scanner;

use Zf2TranslationScanner\File\Source\ScannerAbstract;

class Smarty extends ScannerAbstract
{
    /**
     * @param string $content
     * @return array
     */
    public function parse($content)
    {
        $foundStrings = array_merge(
            $this->_scanForConstruction('/\{t[^\}]*\}([^\}]+)\{\/t(?:\}|[^\}]*\})/', $content),
            $this->_scanForConstruction('/\"([^\"]+)\"\s*\|\s*translate/', $content)
        );

        return $foundStrings;
    }
}