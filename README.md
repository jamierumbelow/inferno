Inferno
=======

**DISCLAIMER: This is a big WIP. I'm also very tired so this is probably extremely incoherent.**

None of the current PHP unit testing frameworks work for me; I want to include a library into my application and be able to run a file at the command line and see my test results. I don't want to install PEAR packages. I don't want to include about a hundred files. I don't want to have to open up the browser.

Inferno is a really quick, lightweight and simple to use PHP unit testing solution. It's based on the [xUnit testing pattern](http://en.wikipedia.org/wiki/XUnit) and it's designed for testing small classes, libraries and other bits of code. It's not meant for massive codebases, rather, it's meant as a nice, coherent way of testing a standalone library or a group of small files.

## Usage

Grab a copy of the source from here or by cloning it. Then simply require the **inferno.php** file in your test file and call `UnitTest::test()`. Each file maps to a test case. A test case is any class that inherits from `UnitTest`.

## Example

Create a new test file in your application. We'll call it *tests/calculator_test.php*:

``` php
require 'lib/calculator.php';
require 'lib/inferno.php';

class CalculatorTest extends UnitTest {
	public function test_add() {
		$this->assert_equal(Calculator::add(1, 2), 3);
	}
}

Inferno::test();
```

We can then run the tests by calling `php tests/calculator_test.php`:

``` bash
$ php tests/calculator_test.php
```