<?php
namespace Zf2TranslationScanner\File;

use Zf2TranslationScanner\FileAbstract;
use Zf2TranslationScanner\WordsContainer;

abstract class OutputAbstract extends FileAbstract
{
    /**
     * @var WordsContainer
     */
    protected $translatableWordsContainer = null;

    /**
     * Get template for file generation
     * @return string
     */
    abstract public function getTemplateFile();

    /**
     * @return WordsContainer
     */
    public function getTranslatableWordsContainer()
    {
        return $this->translatableWordsContainer;
    }

    /**
     * Set words Container
     * @param WordsContainer $translatableWordsContainer
     * @return OutputAbstract
     */
    public function setTranslatableWordsContainer(WordsContainer $translatableWordsContainer)
    {
        $this->translatableWordsContainer = $translatableWordsContainer;
        return $this;
    }

    /**
     * Generate contents of the file using it's data and template
     * @return string
     */
    public function generateContents()
    {
        ob_start();
        include $this->getTemplateFile();
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    /**
     * Save file contents to filesystem
     */
    public function save()
    {
        if ($this->createDirectoryStructure($this->getPath())) {
            $content = $this->generateContents();
            file_put_contents($this->getPathname(), $content);
        }
    }
}