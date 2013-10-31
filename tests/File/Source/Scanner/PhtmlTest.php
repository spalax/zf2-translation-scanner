<?php
require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Test_Translation_File_Source_Scanner_PhtmlTest test case.
 */
class Test_Translation_File_Source_Scanner_PhtmlTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var Test_Translation_File_Source_Scanner_PhtmlTest
	 */
	private $__scannerPhtml;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		$this->__scannerPhtml = new Translation_File_Source_Scanner_Phtml(/* parameters */);
	
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		$this->__scannerPhtml = null;
		parent::tearDown ();
	}
	
	/**
	 * Tests Test_Translation_File_Source_Scanner_PhtmlTest->parse()
	 * 
	 */
	public function testParse() {
	    $words = array('Translatable ID','Translatable %s','err %s');
		$contents = "
		<?=\$this->translate('".$words[0]."')?>
		<?=\$this->translate('".$words[1]."','pupa')?>
		<?=\$this->translate('".$words[2]."',array('druupa'))?>
		";
		$strings = $this->__scannerPhtml->parse($contents);
		$this->assertEquals(3, count($strings));
		
		$i = 0;
		foreach($strings as $string){
			$this->assertEquals($words[$i], $string);
			$i++;
		}
	}

}

