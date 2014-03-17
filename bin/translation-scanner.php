<?php
ini_set('display_errors', true);
chdir(__DIR__);

if (!(@include_once __DIR__ . '/../vendor/autoload.php') && !(@include_once __DIR__ . '/../../../autoload.php')) {
    throw new RuntimeException('Error: vendor/autoload.php could not be found. Did you run php composer.phar install?');
}

$languages = 'en,ru';
$bugsEmail = 'localhost@localdomain';
$resultDir = '/language';
$tmpDir = '/tmp';
$verbosity = false;

$console = new \Zf2TranslationScanner\Console(array(
    'root-dir|p=s' => 'Dir where project root is located',
    'tmp-dir|t=s' => '[Default='.$tmpDir.'] Dir where script can store it is temporary data',
    'indir|i=s'   => '[Default=($root-dir)] Dir to scan for not translated sentences',
    'outdir|o=s' => '[Default=($root-dir)'.$resultDir.'] Dir for output .mo and .po files',
    'email|e=s' => '[Default='.$bugsEmail.']Email of person who responsible for bugs in translations',
    'verbose|v' => 'Print verbose output',
    'languages|l=s' => '[Default='.$languages.'] Define languages using comma, which po\'s will be regenerated and mo\'s built',
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

if (isset($console->v)) {
    $verbosity = $console->v;
}

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
    $parseDir = $console->i;
}

$parseDirs = explode(',', $parseDir);
foreach ($parseDirs as $parseDir) {
    if (!is_dir($parseDir) || !is_readable($parseDir)) {
        $console->error("Invalid parse dir [$parseDir],
                         please define valid one, and check if it is readable", array(), true);
    }
}

$resultDir = $projectRootDir.$resultDir;
if (isset($console->o)) {
    $resultDir = $projectRootDir.$console->o;
}

if (!is_dir($resultDir) || !is_writable($resultDir)) {
    $console->error("Invalid output dir [$resultDir], please define valid one, and check if it is writable", array(), true);
}

if (!is_null($console->getOption('languages'))) {
    $languages = $console->getOption('languages');
}

$locales = array_map(function ($locale){ return trim($locale); },
                     explode(',', $languages));

//if (isset($console->jsdir) && is_dir($console->jsdir)) {
//    $jsDir = $console->jsdir;
//} else {
//    echo "\nInvalid jsdir";
//    exit(1);
//}

$onlyCompress = isset($console->oc) ? !!$console->oc : false;

$bugsEmail = 'localhost@localdomain';
if (!is_null($console->getOption('email'))) {
    $validate = new \Zend\Validator\EmailAddress();
    if ($validate->isValid($console->getOption('email'))) {
        $bugsEmail = $console->getOption('email');
    } else {
        $console->error('Invalid email address ['.$console->getOption('email').']. Please define valid');
    }
}

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

if (count($parseDirs) > 1) {
    $parseDirIterator = new AppendIterator();
    foreach ($parseDirs as $parseDir) {
        $parseDirIterator->append(new RecursiveIteratorIterator(
                                        new RecursiveDirectoryIterator($parseDir)));
    }
} else {
    $parseDirIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($parseDirs[0]));
}

$filesToParse = new \Zf2TranslationScanner\Collector\Source\Files($parseDirIterator, $extensions);

$console->log("Collecting data from files...");
$wordsContainer->addWords($collector->parse($filesToParse)); // Parser_Source

$console->log('Collector finished successful. Found ' . $wordsContainer->countWords() . ' strings in ' . $wordsContainer->countFiles() . ' files');

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


foreach ($locales as $locale) {
    if (!file_exists($resultDir.'/'.strtolower($locale).'.po')) {
        $system->generatePoFile($templateFile->getPathname(), $resultDir.'/'.strtolower($locale).'.po', $locale);
    }
}

$console->log('Updating translation files...');
$translationFiles = new \Zf2TranslationScanner\FilterIterator\File (
    new RecursiveIteratorIterator(new RecursiveDirectoryIterator($resultDir)),
    array('po'));

/* @var $translationFile \SplFileInfo */
foreach ($translationFiles as $translationFile) {
    $console->log("Updating translation file " . $translationFile->getPathname());
    $poFileManager->updateTranslation($translationFile, $templateFile);
}

$console->log('Translation files updated.');

if ($console->getOption('spellfile')) {
    $console->log('Checking strings spelling...');
    $wordsContainer->checkSpelling(new \Zf2TranslationScanner\SpellChecker\Aspell());
    $console->log('Strings spellcheck finished.');
}

$console->log('Cleaning temporary files...');
$system->copy($templateFile->getPathname(), $resultDir);
$system->remove($tmpDir . '/' . $translationWordsDir);
$console->log('Finished successful');
