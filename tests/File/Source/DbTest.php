<?php

require_once 'Translation/File/Source/Db.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Translation_File_Source_Db test case.
 */
class Test_Translation_File_Source_DbTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var Translation_File_Source_Db
	 */
	private $Translation_File_Source_Db;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		$this->Translation_File_Source_Db = new Translation_File_Source_Db('testDir/Test', array('word1', 'word2'));
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		$this->Translation_File_Source_Db = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Tests Translation_File_Source_Db->getContents()
	 */
	public function testGetContents() {
		$this->assertEquals(array('word1', 'word2'), $this->Translation_File_Source_Db->getContents());
	}
	
	/**
	 * Tests Translation_File_Source_Db->getTranslatableWords()
	 */
	public function testGetTranslatableWords() {
		$this->assertEquals(array('word1', 'word2'), $this->Translation_File_Source_Db->getTranslatableWords());
	}

}

