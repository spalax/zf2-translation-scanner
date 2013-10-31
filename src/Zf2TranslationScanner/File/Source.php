<?php
namespace Zf2TranslationScanner\File;

use Zf2TranslationScanner\FileAbstract;
use Zf2TranslationScanner\File\Source\ScannerInterface;

class Source extends FileAbstract
{
    /**
     * List of scanners to search for translatable words
     * @var array
     */
    protected $scanners = array();

    /**
     * @return ScannerInterface[]
     */
    public function getScanners()
    {
        return $this->scanners;
    }

    /**
     * @param ScannerInterface[] $scanners
     * @return Source
     */
    public function setScanners(array $scanners = array())
    {
        $this->scanners = $scanners;
        return $this;
    }

    /**
     * @param ScannerInterface $scanner
     * @return Source
     */
    public function addScanner(ScannerInterface $scanner)
    {
        $this->scanners[] = $scanner;
        return $this;
    }

    /**
     * Get the list of translatable words in file
     * @return array
     */
    public function getTranslatableWords()
    {
        $words = array();
        foreach ($this->getScanners() as $scanner) {
            $words = array_merge($words, $scanner->parse($this->getContents()));
        }
        return $words;
    }
}