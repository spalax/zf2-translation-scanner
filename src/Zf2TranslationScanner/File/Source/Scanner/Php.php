<?php
namespace Zf2TranslationScanner\File\Source\Scanner;

use Zend\View\Helper\EscapeHtml;
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
        if (preg_match('/protected\s\$messageTemplates.*?=/s', $content, $matches)) {
            $tokens = token_get_all($content);

            $namespace = $class = '';
            for ($i=0;$i<count($tokens);$i++) {
                if ($tokens[$i][0] === T_NAMESPACE) {
                    for ($j=$i+1;$j<count($tokens); $j++) {
                        if ($tokens[$j][0] === T_STRING) {
                            $namespace .= '\\'.$tokens[$j][1];
                        } else if ($tokens[$j] === '{' || $tokens[$j] === ';') {
                            break;
                        }
                    }
                }

                if ($tokens[$i][0] === T_CLASS) {
                    for ($j=$i+1;$j<count($tokens);$j++) {
                        if ($tokens[$j] === '{') {
                            $class = $tokens[$i+2][1];
                            break;
                        }
                    }
                }
            }

            if (!class_exists($namespace.'\\'.$class)) return $matches;
            $ref = new \ReflectionClass($namespace.'\\'.$class);
            $props = $ref->getDefaultProperties();
            return $props['messageTemplates'];
        }
        return $matches;
    }
}
