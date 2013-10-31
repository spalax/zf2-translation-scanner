<?php
namespace Zf2TranslationScanner\File\Source;

/**
 * Interface for file scanners that are used to find different kinds of content in files
 */
interface ScannerInterface
{

    /**
     *    Parse provided content.
     *    Return found constructs.
     *
     * @param string $content
     * @return array
     */
    public function parse($content);
}