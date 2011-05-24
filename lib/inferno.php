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
	
	protected $results = array();
	protected $tests = array();
	protected $current_test = '';
	
	/* --------------------------------------------------------------
	 * AUTORUNNER
	 * ------------------------------------------------------------ */

	/**
	 * Run your tests!
	 */
	public static function test() {
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
	}
	
	/* --------------------------------------------------------------
	 * GENERIC METHODS
	 * ------------------------------------------------------------ */
	
	public function __construct() {
		/* stub */
	}
	
	/**
	 * Record a success
	 */
	public function success() {
		$this->results[$this->current_test]['successes'][] = TRUE;
	}
	
	/**
	 * Record a failure
	 */
	public function failure($message) {
		$this->results[$this->current_test]['failures'][] = $message;
	}
	
	/* --------------------------------------------------------------
	 * UNIT TESTING METHODS
	 * ------------------------------------------------------------ */
	
	/**
	 * Assert that an expression meets TRUE boolean. The
	 * base for all the other assertions
	 */
	public function assert($expression, $message = '') {
		if ((bool)($expression) == TRUE) {
			$this->success();
		} else {
			$message = ($message) ? $message : (string)($expression) . " did not equate to TRUE";
			throw new UnitTestFailure($message);
		}
	}
	
	public function assert_equal($one, $two, $message = '') {
		$message = ($message) ? $message : "$one did not equal $two";
		$this->assert(($one == $two), $message);
	}
	
	/* --------------------------------------------------------------
	 * TEST RUNNING METHODS
	 * ------------------------------------------------------------ */
	
	public function run() {
		$this->get_tests();
		$this->run_tests();
		die(var_dump($this));
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
	
	/**
	 * Run each test
	 */
	public function run_tests() {
		foreach ($this->tests as $test) {
			$this->current_test = $test;
			
			try {
				call_user_func_array(array($this, $test), array());
			} catch (Exception $e) {
				if (get_class($e) == 'UnitTestFailure') {
					$this->failure($e->getMessage());
				}
			}
		}
	}
}

/* --------------------------------------------------------------
 * EXCEPTIONS
 * ------------------------------------------------------------ */

class UnitTestFailure extends Exception { }