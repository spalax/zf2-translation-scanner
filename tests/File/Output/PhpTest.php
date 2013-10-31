<?php

require_once 'Translation/File/Output/Php.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Translation_File_Output_Php test case.
 */
class Test_Translation_File_Output_PhpTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var Translation_File_Output_Php
	 */
	private $Translation_File_Output_Php;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		$this->Translation_File_Output_Php = new Translation_File_Output_Php('test.php');
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		$this->Translation_File_Output_Php = null;
		parent::tearDown ();
	}
	
	/**
	 * Tests Translation_File_Output_Php->getTemplateFile()
	 */
	public function testGetTemplateFile() {
		$this->assertStringEndsWith("Php.phtml", $this->Translation_File_Output_Php->getTemplateFile());
		$this->assertFileExists($this->Translation_File_Output_Php->getTemplateFile());	
	}
	
	/**
	 * Tests Translation_File_Output_Php->generateContents
	 */
	public function testGenerateContents(){
		$container = new Translation_WordsContainer();
		$container->addWord(new Translation_Word('test1'));
		$container->addWord(new Translation_Word('test2'));
		$container->addWord(new Translation_Word('test3'));
		$container->addWord(new Translation_Word('test4'));
		$container->addWord(new Translation_Word('test5'));
		$this->Translation_File_Output_Php->setTranslatableWordsContainer($container);
		$this->assertEquals(1, preg_match('/<\?php\s+(_\(\"([^\"]+)\"\);\n)+\s+\?>/', $this->Translation_File_Output_Php->generateContents()));
	}
}

