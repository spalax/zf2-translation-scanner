<?php

require_once 'Translation/File/Source/Scanner/JavaScript.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Translation_File_Source_Scanner_JavaScript test case.
 */
class Test_Translation_File_Source_Scanner_JavaScriptTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var Translation_File_Source_Scanner_JavaScript
	 */
	private $Translation_File_Source_Scanner_JavaScript;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		$this->Translation_File_Source_Scanner_JavaScript = new Translation_File_Source_Scanner_JavaScript(/* parameters */);
	
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		$this->Translation_File_Source_Scanner_JavaScript = null;
		
		parent::tearDown ();
	}

	/**
	 * Tests Translation_File_Source_Scanner_JavaScript->parse()
	 */
	public function testParse() {
		$contents = "
		\"__(____"."('Translatable  \\' 1#')'\"__;'\")
		__(____"."(\"Translatable ' 2#\")'\"__;'\"
		__(____"."(\"Translatable \\\" 3#\")'\"__;'\"
		__(____"."(\"Translatable (%3) [%1] \\\" 4#\", param)'\"__;'\"
		__(____"."('Translatable (%3) [%1] \" 5#',  param)'\"__;'\"
		";
		
		$strings = $this->Translation_File_Source_Scanner_JavaScript->parse($contents);
		$this->assertEquals(5, count($strings));
		foreach($strings as $string){
			$this->assertEquals(1, preg_match('/^Translatable.+#$/', $string));						
		}
	}

}

