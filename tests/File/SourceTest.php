<?php

require_once 'Translation/File/Source.php';
require_once 'vfsStream/vfsStream.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Translation_File_Source test case.
 */
class Test_Translation_File_SourceTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var Translation_File_Source
	 */
	private $Translation_File_Source;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		vfsStream::setup('exampleDir');
        $file = vfsStream::newFile('test1.php');
		vfsStreamWrapper::getRoot()->addChild($file);
		$file->setContent('__'.'("test1"); __'.'(\'test2\')');
		$this->Translation_File_Source = new Translation_File_Source(vfsStream::url('exampleDir/test1.php'));
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		$this->Translation_File_Source = null;

		parent::tearDown ();
	}
	
	/**
	 * Tests Translation_File_Source->getScanners()
	 */
	public function testGetSetScanners() {
		$this->assertEquals(0, count($this->Translation_File_Source->getScanners()));
		$this->Translation_File_Source->setScanners(array(new Translation_File_Source_Scanner_Php()));
		$this->assertEquals(1, count($this->Translation_File_Source->getScanners()));
	}
	
	/**
	 * Tests Translation_File_Source->addScanner()
	 */
	public function testAddScanner() {
		$this->assertEquals(0, count($this->Translation_File_Source->getScanners()));
		$this->Translation_File_Source->addScanner(new Translation_File_Source_Scanner_Php());
		$this->assertEquals(1, count($this->Translation_File_Source->getScanners()));
	}
	
	/**
	 * Tests Translation_File_Source->getTranslatableWords()
	 */
	public function testGetTranslatableWords() {
		$this->Translation_File_Source->addScanner(new Translation_File_Source_Scanner_Php());
		$words = $this->Translation_File_Source->getTranslatableWords();
		$this->assertEquals(2, count($words));
		$this->assertContains('test1', $words);
		$this->assertContains('test2', $words);
	}

}

