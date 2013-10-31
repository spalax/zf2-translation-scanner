<?php

require_once 'Translation/File/Output/JavaScript.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Translation_File_Output_JavaScript test case.
 */
class Test_Translation_File_Output_JavaScriptTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var Translation_File_Output_JavaScript
	 */
	private $Translation_File_Output_JavaScript;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
        $this->Translation_File_Output_JavaScript = new Translation_File_Output_JavaScript('test.js');
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		$this->Translation_File_Output_JavaScript = null;
		parent::tearDown ();
	}
	
	/**
	 * Tests Translation_File_Output_JavaScript->getTemplateFile()
	 */
	public function testGetTemplateFile() {
		$this->assertStringEndsWith("JavaScript.phtml", $this->Translation_File_Output_JavaScript->getTemplateFile());
		$this->assertFileExists($this->Translation_File_Output_JavaScript->getTemplateFile());
	}
	
	/**
	 * Tests Translation_File_Output_JavaScript->generateContents
	 */
	public function testGenerateContents(){
		$container = new Translation_WordsContainer();
		$container->addWord(new Translation_Word('test1'));
		$container->addWord(new Translation_Word('test2'));
		$container->addWord(new Translation_Word('test3'));
		$container->addWord(new Translation_Word('test4'));
		$container->addWord(new Translation_Word('test5'));
		$this->Translation_File_Output_JavaScript->setTranslatableWordsContainer($container);
		$this->assertEquals(1, preg_match('/\(\{(\s+\'([^\']+)\'\s:\s\'\',?\n)+\}\)/', $this->Translation_File_Output_JavaScript->generateContents()));
	}
	
}