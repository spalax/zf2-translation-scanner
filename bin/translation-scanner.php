<?php
ini_set('display_errors', true);
chdir(__DIR__);

if (!(@include_once __DIR__ . '/../vendor/autoload.php') && !(@include_once __DIR__ . '/../../../autoload.php')) {
    throw new RuntimeException('Error: vendor/autoload.php could not be found. Did you run php composer.phar install?');
}

$console = new \Zf2TranslationScanner\Console(array(
    'root-dir|p=s' => 'Dir where project root is located',
    'tmp-dir|t=s' => '[Default=/tmp] Dir where script can store it is temporary data',
    'indir|i=s'   => '[Default=($root-dir)] Dir to scan for not translated sentences',
    'outdir|o=s' => '[Default=($root-dir)/language] Dir for output .mo and .po files',
    'jsdir|js=s' => 'Dir for output .js strings files',
    'verbose|v' => 'Print verbose output',
    'skip-js|sj' => 'Skip js file building',
    'only-compress|oc' => 'Should script only build JS compressed translation file',
    'spellfile|of=s' => 'This file will contains all words who does not pass spell checking',
    'help|h' => 'Show this information'));

try {
    $console->parse();
    if (isset($console->h)) {
        $console->setOption('verbose', true);
        $console->log($console->getUsageMessage(), array(), true, true);
        exit(0);
    }
} catch (\Zend\Console\Exception\ExceptionInterface $e) {
    $console->error($e->getMessage(), array(), true);
}

$translationWordsDir = '__translation';

$verbosity = isset($console->v) ? !!$console->v : false;

$tmpDir = '/tmp';
if (!is_null($console->getOption('tmp-dir'))) {
    $tmpDir = $console->getOption('tmp-dir');
}

if (!is_dir($tmpDir) || !is_writable($tmpDir)) {
    $console->error("Invalid temporary dir [$tmpDir], please define valid one, and check if it is writable", array(), true);
}

$projectRootDir = "";
if (isset($console->p) && is_dir($console->p) && is_readable($console->p)) {
    $projectRootDir = $console->p;
} else {
    $console->error("Invalid project root-dir [$console->p] please define valid root-dir option, and check if it is readable", array(), true);
}

$parseDir = $projectRootDir;
if (isset($console->i)) {
    $parseDir = $projectRootDir.$console->i;
}

if (!is_dir($parseDir) || !is_readable($parseDir)) {
    $console->error("Invalid parse dir [$parseDir], please define valid one, and check if it is readable", array(), true);
}

$resultDir = $projectRootDir.'/language';
if (isset($console->o)) {
    $resultDir = $projectRootDir.$console->o;
}

if (!is_dir($resultDir) || !is_writable($resultDir)) {
    $console->error("Invalid output dir [$resultDir], please define valid one, and check if it is writable", array(), true);
}

//if (isset($console->jsdir) && is_dir($console->jsdir)) {
//    $jsDir = $console->jsdir;
//} else {
//    echo "\nInvalid jsdir";
//    exit(1);
//}

$onlyCompress = isset($console->oc) ? !!$console->oc : false;

$bugsEmail = '';

$extensions = array('php' => array('Php'),
                    'phtml' => array('Phtml'),
                    'xml' => array('Xml'),
                    'json' => array('Json'));

$system = new \Zf2TranslationScanner\System();

$wordsContainer = new \Zf2TranslationScanner\WordsContainer();

/**
 * Parse project. Find All phrases for translation
 */
$console->log('Cleaning temporary files...');
$system->remove($tmpDir . '/' . $translationWordsDir);

$console->log("Starting to collect translatable strings...");

$collector = new \Zf2TranslationScanner\Collector();

$filesToParse = new \Zf2TranslationScanner\Collector\Source\Files(
    new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($parseDir)
    ),
    $extensions);

$console->log("Collecting data from files...");
$wordsContainer->addWords($collector->parse($filesToParse)); // Parser_Source

$console->log('Collector finished successful. Found ' . $wordsContainer->countWords() . ' strings in ' . $wordsContainer->countFiles() . ' files');

if (!$console->getOption('only-compress')) {

    $console->log('Generating translation sandbox...');
    foreach ($wordsContainer->getFiles() as $fileName) {
        $file = new \Zf2TranslationScanner\File\Output\Php(str_replace($projectRootDir, $tmpDir . '/' . $translationWordsDir, $fileName));
        $file->setTranslatableWordsContainer(new \Zf2TranslationScanner\WordsContainer($wordsContainer->getWordsFromFile($fileName)));
        $file->save();
    }
    $console->log('Translation sandbox generated.');

    $console->log('Generating new Portable Object Template...');
    $poFileManager = new \Zf2TranslationScanner\FileManager\Po($system);

    $templateFile = new SplFileInfo($poFileManager->createTemplateFile($tmpDir . '/' . $translationWordsDir, $bugsEmail));

    $console->log('Updating translation files...');
    $translationFiles = new \Zf2TranslationScanner\FilterIterator\File (
        new RecursiveIteratorIterator(new RecursiveDirectoryIterator($resultDir)),
        array('po'));
    foreach ($translationFiles as $translationFile) {
        $locale = basename(dirname($translationFile->getPath()));
        $console->log("Updating translation file for locale " . $locale . '...');
        $poFileManager->updateTranslation($translationFile, $templateFile);
    }
    $console->log('Translation files updated.');

    if ($console->getOption('spellfile')) {
        $console->log('Checking strings spelling...');
        $wordsContainer->checkSpelling(new \Zf2TranslationScanner\SpellChecker\Aspell());
        $console->log('Strings spellcheck finished.');
    }
}

//$moFiles = new Translation_FilterIterator_File(
//                              new RecursiveIteratorIterator(new RecursiveDirectoryIterator($resultDir)),
//                              array('mo'));
//
//require_once 'Zend/Translate.php';
//foreach($moFiles as $moFile){
//     $translator = new Zend_Translate('gettext', $moFile->getPathname(), basename(dirname($moFile->getPath())));
//     foreach($jsWordsContainer->getValues() as $word) {
//          $word->setTranslation($translator->_($word->getText()));
//     }
//     $locale = implode('-', explode('_', strtolower(basename(dirname($moFile->getPath())))));
//     $console->log("Generating ".$locale." localization...");
//     $translationJsFile = new Translation_File_Output_JavaScript("{$jsDir}{$locale}/i18n.js");
//     $translationJsFile->setTranslatableWordsContainer($jsWordsContainer);
//     $translationJsFile->save();
//     unset($translator);
//}

//$console->log("JavaScript localization finished.");

$console->log('Cleaning temporary files...');
$system->remove($tmpDir . '/' . $translationWordsDir);
$system->remove($tmpDir . '/messages.pot');
$console->log('Finished successful');
