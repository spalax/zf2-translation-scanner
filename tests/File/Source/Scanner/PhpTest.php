<?php

require_once 'Translation/File/Source/Scanner/Php.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Translation_File_Source_Scanner_Php test case.
 */
class Test_Translation_File_Source_Scanner_PhpTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var Translation_File_Source_Scanner_Php
	 */
	private $Translation_File_Source_Scanner_Php;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();

		$this->Translation_File_Source_Scanner_Php = new Translation_File_Source_Scanner_Php(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		$this->Translation_File_Source_Scanner_Php = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Tests Translation_File_Source_Scanner_Php->parse()
	 */
	public function testParse() {
		$contents = "
		\"__(____"."('Translatable \\' 1#')'\"__;'\")
		__(____"."(\"Translatable ' 2#\")'\"__;'\"
		__(____"."(\"Translatable \\\" 3#\")'\"__;'\"
		__(____"."(\"Translatable (%3) [%1] \\\" 4#\", param)'\"__;'\"
		__(____"."('Translatable (%3) [%1] \" 5#',  param)'\"__;'\\" .'
		protected $_message'.'Templates = array(
        self::LOG_USERADD             => "Translatable \' [%1]Added user %2 (%3) 6#",
        self::LOG_UNITADD             => "Translatable \\" [%1]Added panel %2 (%3) 7#",
	    );';
		
		$strings = $this->Translation_File_Source_Scanner_Php->parse($contents);
		$this->assertEquals(7, count($strings));
		foreach($strings as $string){
			$this->assertEquals(1, preg_match('/^Translatable.*#$/', $string));
		}
	}

}

