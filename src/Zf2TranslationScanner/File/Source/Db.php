<?php
namespace Zf2TranslationScanner\File\Source;

use Zf2TranslationScanner\File\Source;

class Db extends Source
{
    /**
     * Variable to keep contents of db column
     * @var array
     */
    protected $_content;

    /**
     * Db column file should receive it's name and content on creation
     * @param String $file_name
     * @param array $content
     */
    public function __construct($file_name, $content)
    {
        parent::__construct($file_name);
        $this->_content = $content;
    }

    /**
     * Db column file keeps it's contents in $_content variable
     * @see Translation_File::getContents()
     */
    public function getContents()
    {
        return $this->_content;
    }

    /**
     * Db source is not scanned. It's content is the list of translatable words
     * @see Translation_File_Source::getTranslatableWords()
     */
    public function getTranslatableWords()
    {
        return $this->getContents();
    }
}