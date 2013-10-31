<?php
/**
 * Translation_Word test case.
 */
require_once 'Translation/Word.php';

require_once 'PHPUnit/Framework/TestCase.php';
class Test_Translation_WordTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var Translation_Word
	 */
	private $Translation_Word;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		$this->Translation_Word = new Translation_Word(
										'testWord', 
										'testTranslation', 
										array('../testdir/testfile'=>array(2, 10)),
										true);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		$this->Translation_Word = null;
		parent::tearDown ();
	}
	
	/**
	 * Tests Translation_Word->__construct()
	 */
	public function test__construct() {
		$this->assertEquals('testWord', $this->Translation_Word->getText());
		$this->assertEquals('testTranslation', $this->Translation_Word->getTranslation());
		$this->assertEquals(true, $this->Translation_Word->getIsMisspelled());
		$this->assertArrayHasKey('../testdir/testfile', $this->Translation_Word->getOccurences());
	}
	
	
	/**
	 * Tests Translation_Word->getText()
	 * Tests Translation_Word->setText()
	 */
	public function testSetGetText(){
		$word = 'newWord';
		$this->Translation_Word->setText($word);
		$this->assertEquals($word, $this->Translation_Word->getText());
	}
	
	/**
	 * Tests Translation_Word->getTranslation()
	 */
	public function testGetTranslation() {
		$this->Translation_Word->setTranslation('newTranslation');
		$this->assertEquals('newTranslation', $this->Translation_Word->getTranslation());
	}
	
	/**
	 * Tests Translation_Word->getIsMisspelled()
	 */
	public function testGetIsMisspelled() {
		$this->assertEquals(true, $this->Translation_Word->getIsMisspelled());
		$this->Translation_Word->setIsMisspelled(false);
		$this->assertEquals(false, $this->Translation_Word->getIsMisspelled());
	}
	
	/**
	 * Tests Translation_Word->getOccurences()
	 */
	public function testGetOccurences() {
		$this->Translation_Word->addOccurence('newFile', 1);
		$occurences = $this->Translation_Word->getOccurences();
		$this->assertArrayHasKey('newFile', $occurences);
		$this->assertContains(1, $occurences['newFile']);
	}
	
	/**
	 * Tests Translation_Word->occuredInFile()
	 */
	public function testOccuredInFile() {
		$this->assertEquals(true, $this->Translation_Word->occuredInFile('../testdir/testfile'));
	}
	
	/**
	 * Tests Translation_Word->__toString()
	 */
	public function test__toString() {
		$this->assertEquals('testWord', $this->Translation_Word->__toString());
	}
	
}