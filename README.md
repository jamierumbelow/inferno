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
<?php

require 'lib/calculator.php';
require 'lib/inferno.php';

class CalculatorTest extends UnitTest {
	public function test_add() {
		$this->assert_equal(Calculator::add(1, 2), 3);
	}
}

Inferno::test();
```

We'll also add the basic class and method so we don't get fatal errors:

``` php
<?php

class Calculator {
	public static function add() { }
}
```

We can then run the tests by calling `php tests/calculator_test.php`:

``` bash
$ php tests/calculator_test.php
✘ 
----------------------------------

Failures!
=========

test_add():
	- NULL doesn't equal 3


----------------------------------

Not so cool :( there was a problem running your tests!
Ran 1 assertion(s) in 0.000073 seconds
```

We can implement our method...

``` php
<?php

// ...
	public static function add($one, $two) {
		return $one + $two;
	}
```

...and run our tests again...

``` bash
$ php tests/calculator_test.php
✓ 
----------------------------------

Cool! All your tests ran perfectly.
Ran 1 assertion(s) in 0.000058 seconds
```

And we have a working test suite!

## Assertions

Inferno contains a bunch of useful assertions to test your code. All the method names are self-explanatory, and if you get lost, dive into the code. It's exceedingly well commented and I'm going to fight to keep it that way.

``` php
<?php

public function assert($expression, $message = '');
public function assert_true($expression, $message = '');
public function assert_false($expression, $message = '');
public function assert_equal($one, $two, $message = '');
public function assert_not_equal($one, $two, $message = '');
public function assert_equivalent($one, $two, $message = '');
public function assert_type($value, $type, $message = '');
public function assert_class($value, $class, $message = '');
public function assert_empty($value, $message = '');
public function assert_not_empty($value, $message = '');
public function assert_has_key($array, $key, $message = '');
public function assert_doesnt_have_key($array, $key, $message = '');
```

## Customisation

Inferno is very easy to customise too. The test runner itself is part of the `UnitTest` class, and it is built up from several methods which you can overload. We'll subclass UnitTest and output the test results as JSON instead:

``` php
<?php

class JsonUnitTest extends UnitTest {
	public function print_results() {
		echo json_encode(array(
		
			// $this->results is an array with all the test
			// results from every test in this test case.
			'results' => $this->results,
			
			// $this->assertion_count is the number of assertions
			// the runner ran in total
			'assertion_count' => $this->assertion_count,
			
			// $this->start_time and end_time are timestamps when
			// the processing started and ended
			'processing_time_seconds' => number_format(($this->end_time - $this->start_time), 6)
			
		);
	}
}
```

Just make sure your test class `extends` from `JsonUnitTest` and then when you run your tests...

``` bash
$ php tests/calculator_test.php
{"results":{"test_add":{"successes":[true]}},"assertion_count":0,"processing_time_seconds":"0.000051"}
```

Check out the class itself for more information on which methods should be overloaded and what information is accessible through variables.

## License

Copyright (c) 2011 Jamie Rumbelow <jamie@jamierumbelow.net>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.