<?php
/**
 * Inferno
 *
 * Quick, lightweight and simple to use 
 * PHP unit testing library
 *
 * @author Jamie Rumbelow <http://jamierumbelow.net>
 * @version 0.1.0
 * @copyright Copyright (c)2011 Jamie Rumbelow
 * @package default
 **/

/* --------------------------------------------------------------
 * UNIT TESTER
 * ------------------------------------------------------------ */

class UnitTest {
	
	/* --------------------------------------------------------------
	 * VARIABLES
	 * ------------------------------------------------------------ */
	
	protected $results = array(
		'passes' 	=> array(),
		'failures'	=> array(),
		'errors'	=> array()
	);
	protected $tests = array();
	
	/* --------------------------------------------------------------
	 * GENERIC METHODS
	 * ------------------------------------------------------------ */
	
	public function __construct() {
		/* stub */
	}
	
	/* --------------------------------------------------------------
	 * TEST RUNNING METHODS
	 * ------------------------------------------------------------ */
	
	public function run() {
		$this->get_tests();
		$this->run_tests();
		$this->print_results();
	}
	
	/**
	 * Loop through all the methods that begin with
	 * test_ and add them to the $this->tests array.
	 */
	public function get_tests() {
		$methods = get_class_methods($this);
		
		foreach ($methods as $method) {
			if (substr($method, 0, 5) == 'test_') {
				$this->tests[] = $method;
			}
		}
	}
}

/* --------------------------------------------------------------
 * AUTORUNNER
 * ------------------------------------------------------------ */

// Get all the declared classes
$classes = get_declared_classes();

// Loop through them and if they're subclasses of
// UnitTest then instanciate and run them!
foreach ($classes as $class) {
	if (is_subclass_of($class, 'UnitTest')) {
		$instance = new $class();
		$instance->run();
	}
}