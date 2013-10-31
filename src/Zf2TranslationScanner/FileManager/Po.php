<?php
namespace Zf2TranslationScanner\FileManager;

use Zf2TranslationScanner\FileManagerAbstract;
use Zf2TranslationScanner\System;

class Po extends FileManagerAbstract
{

    /**
     * System object. Used to call system commands
     * @var System
     */
    protected $_system;

    /**
     * @param System $system
     */
    public function __construct(System $system)
    {
        $this->_system = $system;
    }

    /**
     * Create Portable Object Template File
     * @param string $sourceDir
     * @param string $bugsEmail
     * @return string
     */
    public function createTemplateFile($sourceDir, $bugsEmail)
    {
        $this->_system->generatePotFile($sourceDir, $bugsEmail);
        return 'messages.pot';
    }

    /**
     * Update translation file with template file
     * @param \SplFileInfo $translationFile
     * @param \SplFileInfo $templateFile
     */
    public function updateTranslation(\SplFileInfo $translationFile, \SplFileInfo $templateFile)
    {
        $this->_system->updatePoFile($translationFile->getRealPath(), $templateFile->getRealPath());
        $this->_system->generateMoFileFromPo($translationFile->getRealPath(), preg_replace('/\.po$/', '.mo', $translationFile->getRealPath()));
    }
}