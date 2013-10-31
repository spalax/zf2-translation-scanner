<?php

require_once 'Translation/Collector/Source/Files.php';
require_once 'vfsStream/vfsStream.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Translation_Collector_Source_Files test case.
 */
class Test_Translation_Collector_Source_FilesTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Translation_Collector_Source_Files
	 */
	private $Translation_Collector_Source_Files;

	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();

		vfsStream::setup('exampleDir');

		$dir = vfsStream::newDirectory('subDir1');

        vfsStreamWrapper::getRoot()->addChild($dir);
        vfsStreamWrapper::getRoot()->addChild(vfsStream::newFile('test1.php'));

        $dir->addChild(vfsStream::newFile('test2.php'));
        $dir->addChild(vfsStream::newFile('test3.js'));
        $dir->addChild(vfsStream::newFile('test4.xml'));

        $extentions = array('php'=>array('PHP'));

        $this->Translation_Collector_Source_Files = new Translation_Collector_Source_Files(
					new RecursiveIteratorIterator(new RecursiveDirectoryIterator(vfsStream::url('exampleDir'))),
					$extentions);

	}

	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		$this->Translation_Collector_Source_Files = null;

		parent::tearDown ();
	}

	/**
	 * Tests Translation_Collector_Source_Files->current()
	 */
	public function testCurrent() {
		foreach($this->Translation_Collector_Source_Files as $file){
			$this->assertInstanceOf("Translation_File_Source", $file);
			$this->assertEquals('php', $file->getExtention());
			$scanners = $file->getScanners();
			$this->assertEquals(1, count($scanners));
			$this->assertInstanceOf("Translation_File_Source_Scanner_PHP", $scanners[0]);
		}
	}
}