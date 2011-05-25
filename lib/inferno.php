<?php
/**
 * Inferno
 *
 * Quick, lightweight and simple to use 
 * PHP unit testing library
 *
 * @author Jamie Rumbelow <http://jamierumbelow.net>
 * @version 0.1.0-pre
 * @copyright Copyright (c)2011 Jamie Rumbelow
 * @package inferno
 **/

/* --------------------------------------------------------------
 * UNIT TESTER
 * ------------------------------------------------------------ */

class UnitTest {
	
	/* --------------------------------------------------------------
	 * VARIABLES
	 * ------------------------------------------------------------ */
	
	public $results 		= array();
	public $tests 			= array();
	public $current_test 	= '';
	
	public $start_time 		= 0;
	public $end_time 		= 0;
	public $assertion_count	= 0;
	
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
				
				// Only run tests if we have a test_ method
				$methods = get_class_methods($instance);
				$run = FALSE;
				
				foreach ($methods as $method) {
					if (substr($method, 0, 5) == 'test_') {
						$run = TRUE;
					}
				}
				
				if ($run) {
					$instance->run();
				}
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
	
	/**
	 * Record an error
	 */
	public function error($message) {
		$this->results[$this->current_test]['errors'][] = $message;
	}
	
	/**
	 * Overload these methods to have code called
	 * before each test
	 */
	public function set_up() { /* Overload */ }
	public function tear_down() { /* Overload */ }
	
	/* --------------------------------------------------------------
	 * UNIT TESTING METHODS
	 * ------------------------------------------------------------ */
	
	/**
	 * Assert that an expression is TRUE boolean. The
	 * base for all the other assertions
	 */
	public function assert($expression, $message = '') {
		if ((bool)($expression) == TRUE) {
			$this->success();
		} else {
			$message = ($message) ? $message : (string)($expression) . " doesn't equate to TRUE";
			throw new UnitTestFailure($message);
		}
	}
	public function assert_true($e, $m = '') { $this->assert($e, $m); }
	
	/**
	 * Assert that the expression is FALSE
	 */
	public function assert_false($expression, $message = '') {
		$this->assert(!$expression, $message);
	}
	
	/**
	 * Assert that two values are equal ( == )
	 */
	public function assert_equal($one, $two, $message = '') {
		$message = ($message) ? $message : $this->_($one) . " doesn't equal " . $this->_($two);
		$this->assert(($one == $two), $message);
	}
	
	/**
	 * Assert that two values are not equal ( !== )
	 */
	public function assert_not_equal($one, $two, $message = '') {
		$message = ($message) ? $message : $this->_($one) . " equals " . $this->_($two) . ", and it shouldn't!";
		$this->assert(($one !== $two), $message);
	}
	
	/**
	 * Assert that two values are equivalent ( === )
	 */
	public function assert_equivalent($one, $two, $message = '') {
		$message = ($message) ? $message : $this->_($one) . " is not equivalent to " . $this->_($two);
		$this->assert(($one === $two), $message);
	}
	
	/**
	 * Assert that a value is a specific type (using gettype())
	 */
	public function assert_type($value, $type, $message = '') {
		$message = ($message) ? $message : $this->_($value) . " is not the type '" . $type . "'";
		$this->assert((gettype($value) == $type), $message);
	}
	
	/**
	 * Assert that a value is an instance of a specific class
	 */
	public function assert_class($value, $class, $message = '') {
		$message = ($message) ? $message : $this->_($value) . " is not an instance of the class '" . $class . "'";
		$this->assert((get_class($value) == $class), $message);
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
	
	/**
	 * Run each test
	 */
	public function run_tests() {
		$this->start_time = microtime(TRUE);
		
		set_error_handler(array($this, 'error_handler'));
		
		foreach ($this->tests as $test) {
			$this->current_test = $test;
			$this->set_up();
			
			try {
				call_user_func_array(array($this, $test), array());
			} catch (Exception $e) {
				if (get_class($e) == 'UnitTestFailure') {
					$this->failure($e->getMessage());
				} else {
					$this->error($e->getMessage());
				}
			}
			
			$this->tear_down();
		}
		
		restore_error_handler();
		
		$this->end_time = microtime(TRUE);
	}
	
	/**
	 * Loop through the test results and output them
	 * to the console!
	 */
	public function print_results() {
		$failures = array();
		$errors = array();
		$good = TRUE;
		
		// Print out the running status of each method.
		foreach ($this->results as $unit_test => $results) {
			foreach ($results as $result => $values) {
				foreach ($values as $value) {
					$this->assertion_count++;
					
					switch ($result) {
						case 'failures': echo('✘ '); $failures[$unit_test][] = $value; break;
						case 'errors': echo('! '); $errors[$unit_test][] = $value; break;
					
						default:
						case 'successes': echo('✓ '); break;
					}
				}
			}
		}
		
		echo("\n----------------------------------\n\n");
		
		// Do we have any failures?
		if ($failures) {
			$good = FALSE;
			
			foreach ($failures as $unit_test => $messages) {
				echo("Failures!\n");
				echo("=========\n\n");
				
				echo($unit_test . "():\n");
				
				foreach ($messages as $message) {
					echo("\t- " . $message."\n");
				}
				
				echo("\n");
			}
			
			echo("\n----------------------------------\n\n");
		}
		
		// Do we have any failures?
		if ($errors) {
			$good = FALSE;
			
			foreach ($errors as $unit_test => $messages) {
				echo("Errors!\n");
				echo("=======\n\n");
				
				echo($unit_test . "():\n");
				
				foreach ($messages as $message) {
					echo("\t- " . $message."\n");
				}
			}
			
			echo("\n----------------------------------\n\n");
		}
		
		// Good or bad?
		if ($good) {
			echo("\033[0;32mCool! All your tests ran perfectly.\033[0m\n");
		} else {
			echo("\033[0;31mNot so cool :( there was a problem running your tests!\033[0m\n");
		}
		
		// Finally, test stats
		echo("Ran " . 
			 $this->assertion_count . 
			 " assertion(s) in " . 
			 number_format(($this->end_time - $this->start_time), 6) . 
			 " seconds");
	}
	
	/* --------------------------------------------------------------
	 * UTILITY/HELPERS
	 * ------------------------------------------------------------ */
	
	/**
	 * Handle PHP errors
	 */
	public function error_handler($no, $str) {
		$this->error($str);
	}
	
	/**
	 * Format a value and return it as an
	 * output friendly string
	 */
	public function _($value) {
		if (is_null($value)) {
			return 'NULL';
		} else {
			return $value;
		}
	}
}

/* --------------------------------------------------------------
 * EXCEPTIONS
 * ------------------------------------------------------------ */

class UnitTestFailure extends Exception { }