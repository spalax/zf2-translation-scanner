<?php
namespace Zf2TranslationScanner\SpellChecker;

use Zf2TranslationScanner\SpellCheckerAbstract;

class Aspell extends SpellCheckerAbstract
{
    /**
     * Check whether given phrase is spelled right
     *
     * @param string $phrase
     */
    public function isValid($phrase)
    {
        $proc = popen('echo "' . $phrase . '" | aspell -a', 'r');
        $data = '';
        while (!feof($proc)) {
            $data .= fread($proc, 1);
        }
        pclose($proc);
        if (strpos($data, '&') === false) {
            return true;
        }
        return false;
    }
}