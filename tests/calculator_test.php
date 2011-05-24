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

require 'lib/calculator.php';
require 'lib/inferno.php';

class CalculatorTest extends UnitTest {
	public function test_add() {
		$this->assert_equal(Calculator::add(1, 2), 3);
	}
}