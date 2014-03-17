<?php
namespace Zf2TranslationScanner\File\Source\Scanner;

use Zf2TranslationScanner\File\Source\ScannerAbstract;

class Php extends ScannerAbstract
{
    public function parse($content)
    {
        $foundPhrases = array_merge($this->_scanForConstruction('/\_\_\(\'(([^\']|\\\\.)+)\'\)/', $content),
            $this->_scanForConstruction('/\_\_\(\"(([^\"]|\\\\.)+)\"\)/', $content),
            $this->_scanForConstruction('/\_\_\(\"(([^\"]|\\\\.)+)\",[^\)]+/', $content),
            $this->_scanForConstruction('/\_\_\(\'(([^\']|\\\\.)+)\',[^\)]+/', $content),
            $this->_scanForArrayConstruction($content)
        );
        return $foundPhrases;
    }

    /**
     * Scan content for class parameter that contains array construction
     * with words for translation.
     *
     * @param string $content content
     * @return array $matches
     */
    private function _scanForArrayConstruction($content)
    {
        $matches = array();
        if (preg_match('/protected\s\$\messageTemplates.*?=.*?array\((.*?);/s', $content, $matches)) {
            if (preg_match_all('/.*?=>.*?\"(([^\\\"]|\\\\.)+)\"/', trim($matches[1]), $m)) {
                $matches = $m[1];
            }
        }
        return $matches;
    }
}
