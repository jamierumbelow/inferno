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

require 'lib/inferno.php';

class InfernoTest extends UnitTest {
	
	/* --------------------------------------------------------------
	 * PRE/POST TEST
	 * ------------------------------------------------------------ */
	
	public function set_up() {
		$this->inferno = new UnitTest();
		$this->inferno->current_test = 'inferno_test';
	}
	
	/* --------------------------------------------------------------
	 * ASSERTION TEST CASES
	 * ------------------------------------------------------------ */
	
	public function test_assert_marks_a_success_on_a_successful_test() {
		$this->inferno->assert(TRUE);
		$this->assert_success();
	}
	
	public function test_assert_throws_exception_on_unsuccessful_test() {
		try {
			$this->inferno->assert(FALSE);
			
			// We shouldn't get here, assert() should throw an exception
			$this->failure('assert() did not throw a UnitTestFailure exception on assertion failure!');
		} catch (UnitTestFailure $e) {
			$this->assert_equal($e->getMessage(), "<FALSE> doesn't equate to TRUE");
		}
	}
	
	public function test_assert_logs_failure_but_carries_on_if_quiet_is_true() {
		$this->assert_false($this->inferno->assert(FALSE, '', TRUE));
		$this->assert_failure();
	}
	
	public function test_assert_false() {
		$this->inferno->assert_false(FALSE);
		$this->inferno->assert_quietly()->assert_false(TRUE);
		
		$this->assert_success();
		$this->assert_failure();
	}
	
	public function test_assert_equal() {
		$this->inferno->assert_equal('equal', 'equal');
		$this->inferno->assert_quietly()->assert_equal('equal', 'not equal');
		
		$this->assert_success();
		$this->assert_failure();
	}
	
	public function test_assert_not_equal() {
		$this->inferno->assert_not_equal('equal', 'not equal');
		$this->inferno->assert_quietly()->assert_not_equal('equal', 'equal');
		
		$this->assert_success();
		$this->assert_failure();
	}
	
	public function test_assert_equivalent() {
		$one = new stdClass;
		$two = $one;
		$three = new stdClass;
		
		$this->inferno->assert_equivalent($one, $two);
		$this->inferno->assert_quietly()->assert_equivalent($one, $three);
		
		$this->assert_success();
		$this->assert_failure();
	}
	
	public function test_assert_type() {
		$this->inferno->assert_type('some string', 'string');
		$this->inferno->assert_quietly()->assert_type('some string', 'integer');
		
		$this->assert_success();
		$this->assert_failure();
	}
	
	public function test_assert_class() {
		$this->inferno->assert_class($this->inferno, 'UnitTest');
		$this->inferno->assert_quietly()->assert_class($this->inferno, 'SomeOtherClass');
		
		$this->assert_success();
		$this->assert_failure();
	}
	
	public function test_assert_empty() {
		$this->inferno->assert_empty(array());
		$this->inferno->assert_quietly()->assert_empty(array('some content'));
		
		$this->assert_success();
		$this->assert_failure();
	}
	
	public function test_assert_not_empty() {
		$this->inferno->assert_not_empty(array('some_content'));
		$this->inferno->assert_quietly()->assert_not_empty(array());
		
		$this->assert_success();
		$this->assert_failure();
	}
	
	public function test_assert_has_key() {
		$this->inferno->assert_has_key(array('key' => 'value'), 'key');
		$this->inferno->assert_quietly()->assert_has_key(array('key' => 'value'), 'other key');
		
		$this->assert_success();
		$this->assert_failure();
	}
	
	public function test_assert_doesnt_have_key() {
		$this->inferno->assert_doesnt_have_key(array('key' => 'value'), 'some key');
		$this->inferno->assert_quietly()->assert_doesnt_have_key(array('key' => 'value'), 'key');
		
		$this->assert_success();
		$this->assert_failure();
	}
	
	/* --------------------------------------------------------------
	 * RUNNER TEST CASES
	 * ------------------------------------------------------------ */
	
	public function test_get_tests() {
		$inferno = new InfernoTest();
		$inferno->get_tests();
		
		$this->assert_not_empty($inferno->tests);
		$this->assert(in_array('test_get_tests', $inferno->tests));
	}
	
	public function test_run_tests() {
		$inferno = new InfernoTest();
		$inferno->tests[] = 'run_tests_test_method';
		$inferno->parent = $this;
		
		$inferno->run_tests();
	}
	
	public function run_tests_test_method() {
		$this->parent->success();
	}
	
	/* --------------------------------------------------------------
	 * CUSTOM ASSERTIONS
	 * ------------------------------------------------------------ */
	
	public function assert_success() {
		if (isset($this->inferno->results['inferno_test']['successes'])) {
			$this->assert_true($this->inferno->results['inferno_test']['successes'][0], "There are no test successes!");
		} else {
			$this->failure("There are no test successes!");
		}
	}
	
	public function assert_failure() {
		if (isset($this->inferno->results['inferno_test']['failures'])) {
			$this->assert_not_empty($this->inferno->results['inferno_test']['failures'], "There are no test failures!");
		} else {
			$this->failure("There are no test failures!");
		}
	}
}

UnitTest::test();