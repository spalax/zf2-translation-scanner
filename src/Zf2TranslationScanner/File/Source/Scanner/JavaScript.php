<?php
namespace Zf2TranslationScanner\File\Source\Scanner;

use Zf2TranslationScanner\File\Source\ScannerAbstract;

class JavaScript extends ScannerAbstract
{

    /**
     * @param   string $content
     * @return   array
     * @see  Scanner::parse()
     * @see  ScannerAbstract::parse()
     */
    public function parse($content)
    {
        $matches = array_merge($this->_scanForConstruction('/\_\_\(\'(([^\']|\\\\.)+)\'\)/', $content),
            $this->_scanForConstruction('/\_\_\(\"(([^\"]|\\\\.)+)\"\)/', $content),
            $this->_scanForConstruction('/\_\_\(\"(([^\"]|\\\\.)+)\",[^\)]+/', $content),
            $this->_scanForConstruction('/\_\_\(\'(([^\']|\\\\.)+)\',[^\)]+/', $content)
        );
        return $matches;
    }
}