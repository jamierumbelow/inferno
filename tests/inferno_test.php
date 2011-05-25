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
	public function set_up() {
		$this->inferno = new UnitTest();
		$this->inferno->current_test = 'inferno_test';
	}
	
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
		$this->assert_success();
	}
	
	public function assert_success() {
		$this->assert_true($this->inferno->results['inferno_test']['successes'][0]);
	}
	
	public function assert_failure() {
		$this->assert_equal($this->inferno->results['inferno_test']['failures'][0], "<FALSE> doesn't equate to TRUE");
	}
}

UnitTest::test();