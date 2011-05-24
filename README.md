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

We'll also add the basic class and method so we don't get fatal errors:

``` php
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