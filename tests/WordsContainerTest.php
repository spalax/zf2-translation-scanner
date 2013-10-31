<?php
/**
 * Translation_WordsContainer test case.
 */
require_once 'Translation/WordsContainer.php';
require_once 'Translation/Word.php';
require_once 'Translation/SpellChecker/Aspell.php';

require_once 'PHPUnit/Framework/TestCase.php';
class Test_Translation_WordsContainerTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Translation_WordsContainer
	 */
	private $Translation_WordsContainer;

	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		$this->Translation_WordsContainer = new Translation_WordsContainer(array(new Translation_Word('testWord','translation', array('testdir/testfile'=>array(1)))));
	}

	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		$this->Translation_WordsContainer = null;

		parent::tearDown ();
	}

	/**
	 * Tests Translation_WordsContainer->__construct()
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function test__construct() {
		$this->$this->Translation_WordsContainer->__construct(null);
	}

	/**
	 * Tests Translation_WordsContainer->offsetExists()
	 */
	public function testOffsetExists() {
		$this->assertTrue($this->Translation_WordsContainer->offsetExists('testWord'));
		$this->assertFalse($this->Translation_WordsContainer->offsetExists('nonExistentWord'));
	}

	/**
	 * Tests Translation_WordsContainer->offsetGet()
	 */
	public function testOffsetGet() {
		$this->assertInstanceOf('Translation_Word', $this->Translation_WordsContainer->offsetGet('testWord'));
		$this->setExpectedException('PHPUnit_Framework_Error');
		$this->Translation_WordsContainer->offsetGet('nonexistentWord');
	}

	/**
	 * Tests Translation_WordsContainer->offsetSet()
	 */
	public function testOffsetSet() {
		$this->Translation_WordsContainer['newWord'] = new Translation_Word('newWord');
		$this->assertTrue(isset($this->Translation_WordsContainer['newWord']));
		$this->setExpectedException('Zend_Exception');
		$this->Translation_WordsContainer->offsetSet(1, null);
	}

	/**
	 * Tests Translation_WordsContainer->offsetUnset()
	 */
	public function testOffsetUnset() {
		unset($this->Translation_WordsContainer['testWord']);
		$this->assertEquals(0, count($this->Translation_WordsContainer));
	}

	/**
	 * Tests Translation_WordsContainer->count()
	 */
	public function testCount() {
		$this->assertEquals(1, $this->Translation_WordsContainer->count());
		$this->Translation_WordsContainer->addWord(new Translation_Word('newWord'));
		$this->assertEquals(2, $this->Translation_WordsContainer->count());
		unset($this->Translation_WordsContainer['newWord']);
		$this->assertEquals(1, $this->Translation_WordsContainer->count());
	}

	/**
	 * Tests Translation_WordsContainer->getValues()
	 */
	public function testGetValues() {
		$this->assertTrue(is_array($this->Translation_WordsContainer->getValues()));
		$this->assertArrayHasKey('testWord', $this->Translation_WordsContainer->getValues());
		$this->assertEquals(1, count($this->Translation_WordsContainer->getValues()));
	}

	/**
	 * Tests Translation_WordsContainer->addWord()
	 */
	public function testAddWord() {
		$this->Translation_WordsContainer->addWord(new Translation_Word('addedWord'));
		$this->assertTrue(isset($this->Translation_WordsContainer['addedWord']));
		$this->setExpectedException('PHPUnit_Framework_Error');
		$this->Translation_WordsContainer->addWord('addedString');
	}

	/**
	 * Tests Translation_WordsContainer->addWords()
	 */
	public function testAddWords() {
		$this->Translation_WordsContainer->addWords(array(new Translation_Word('addedWord1')));
		$this->assertTrue(isset($this->Translation_WordsContainer['addedWord1']));
	}

	/**
	 * Tests Translation_WordsContainer->deleteWord()
	 */
	public function testDeleteWord() {
		$this->Translation_WordsContainer->deleteWord(new Translation_Word('testWord'));
		$this->assertEquals(0, count($this->Translation_WordsContainer));
	}

	/**
	 * Tests Translation_WordsContainer->hasWord()
	 */
	public function testHasWord() {
		$this->assertTrue($this->Translation_WordsContainer->hasWord(new Translation_Word('testWord')));
	}

	/**
	 * Tests Translation_WordsContainer->getWordsFromFile()
	 */
	public function testGetWordsFromFile() {
		$this->assertEquals(0, count($this->Translation_WordsContainer->getWordsFromFile('newFile')));
		$this->Translation_WordsContainer->offsetGet('testWord')->addOccurence('newFile', 5);
		$this->assertEquals(1, count($this->Translation_WordsContainer->getWordsFromFile('newFile')));
		$this->arrayHasKey('testWord', $this->Translation_WordsContainer->getWordsFromFile('newFile'));
	}

	/**
	 * Tests Translation_WordsContainer->hasWordsFromFile()
	 */
	public function testHasWordsFromFile() {
		$this->assertTrue($this->Translation_WordsContainer->hasWordsFromFile('testdir/testfile'));
		$this->assertFalse($this->Translation_WordsContainer->hasWordsFromFile('testfile'));
	}

	/**
	 * Tests Translation_WordsContainer->getFiles()
	 */
	public function testGetFiles() {
		$this->assertEquals(1, count($this->Translation_WordsContainer->getFiles()));
		$this->assertEquals(0, count($this->Translation_WordsContainer->getFiles(array('php'))));
		$this->Translation_WordsContainer->offsetGet('testWord')->addOccurence('newFile.php', 5);
		$files = $this->Translation_WordsContainer->getFiles(array('php'));
		$this->assertEquals(1, count($files));
		$this->assertArrayHasKey('newFile.php', $files);
	}

	/**
	 * Tests Translation_WordsContainer->countFiles()
	 */
	public function testCountFiles() {
		$this->assertEquals(1, $this->Translation_WordsContainer->countFiles());
		$this->Translation_WordsContainer->offsetGet('testWord')->addOccurence('newFile.php', 5);
		$this->assertEquals(2, $this->Translation_WordsContainer->countFiles());
	}

	/**
	 * Tests Translation_WordsContainer->countWords()
	 */
	public function testCountWords() {
		$this->assertEquals(1, $this->Translation_WordsContainer->countWords());
		$this->Translation_WordsContainer->addWord(new Translation_Word('newWord'));
		$this->assertEquals(2, $this->Translation_WordsContainer->countWords());
		unset($this->Translation_WordsContainer['newWord']);
		$this->assertEquals(1, $this->Translation_WordsContainer->countWords());
	}

	/**
	 * Tests Translation_WordsContainer->checkSpelling()
	 */
	public function testCheckSpelling() {
		$this->Translation_WordsContainer->checkSpelling(new Translation_SpellChecker_Aspell());
		$this->assertTrue($this->Translation_WordsContainer->offsetGet('testWord')->getIsMisspelled());
	}
}

