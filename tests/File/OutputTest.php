<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'vfsStream/vfsStream.php';

/**
 * Translation_File_Output test case.
 */
class Test_Translation_File_OutputTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Translation_File_Output
	 */
	private $Translation_File_Output;

	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('exampleDir'));
		$this->Translation_File_Output = new Translation_File_Output_JavaScript(vfsStream::url('exampleDir/subdir1/subdir2/test.js'));
	}

	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		$this->Translation_File_Output = null;

		parent::tearDown ();
	}

	/**
	 * Tests Translation_File_Output->setTranslatableWordsContainer()
	 * Tests Translation_File_Output->getTranslatableWordsContainer()
	 */
	public function testSetGetTranslatableWordsContainer(){
		$container = new Translation_WordsContainer();
		$this->Translation_File_Output->setTranslatableWordsContainer($container);
		$this->assertEquals($container, $this->Translation_File_Output->getTranslatableWordsContainer());
		$this->setExpectedException('PHPUnit_Framework_Error');
		$this->Translation_File_Output->setTranslatableWordsContainer(null);
	}

	/**
	 * Tests Translation_File_Output->save()
	 */
	public function testSave(){
		$container = new Translation_WordsContainer();
		$container->addWord(new Translation_Word('test1'));
		$container->addWord(new Translation_Word('test2"'));
		$container->addWord(new Translation_Word('test3\''));

		$this->Translation_File_Output->setTranslatableWordsContainer($container);
		$this->assertFalse(vfsStreamWrapper::getRoot()->hasChild('subdir1'));
		$this->Translation_File_Output->save();
		$this->assertTrue(vfsStreamWrapper::getRoot()->hasChild('subdir1'));
		$this->assertTrue(vfsStreamWrapper::getRoot()->getChild('subdir1')->hasChild('subdir2'));
		$this->assertTrue(vfsStreamWrapper::getRoot()->getChild('subdir1')->getChild('subdir2')->hasChild('test.js'));
		$file = vfsStreamWrapper::getRoot()->getChild('subdir1')->getChild('subdir2')->getChild('test.js');
		$this->assertNotEquals('', $file->getContent());
	}
}

