<?php

require_once 'Translation/File/Source/Scanner/Smarty.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Translation_File_Source_Scanner_Smarty test case.
 */
class Test_Translation_File_Source_Scanner_SmartyTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var Translation_File_Source_Scanner_Smarty
	 */
	private $Translation_File_Source_Scanner_Smarty;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		$this->Translation_File_Source_Scanner_Smarty = new Translation_File_Source_Scanner_Smarty(/* parameters */);
	
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		$this->Translation_File_Source_Scanner_Smarty = null;
		parent::tearDown ();
	}
	
	/**
	 * Tests Translation_File_Source_Scanner_Smarty->parse()
	 */
	public function testParse() {
		$contents = "
		{t}Translatable \\' ')'\"__;'\"1#{/t}
		{t}Translatable ' \")'\"__;'\"2#{/t|quote}
		{t}Translatable \\\" \")'\"__;'\"3#{/t|quote|uppercase}
		";
		$strings = $this->Translation_File_Source_Scanner_Smarty->parse($contents);
		$this->assertEquals(3, count($strings));
		
		foreach($strings as $string){
			$this->assertEquals(1, preg_match('/^Translatable.*#$/', $string));
		}
	}

}

