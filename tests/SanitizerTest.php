<?php

namespace Akoryak\SecurityTests;

use Akoryak\Security\Sanitizer;
use Akoryak\Security\Exception\SanitizerException;

/**
 * @coversDefaultClass Akoryak\Security\Sanitizer
 */
class SanitizerTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var Sanitizer
	 */
	private $sanitizer = null;

	protected function setUp() {
		$this->instance = new Sanitizer();
	}

	protected function tearDown() {
		unset( $this->instance );
	}

	/**
	 * @covers ::stringValue
	 */
	public function testStringValueShouldThrowException() {
		$this->expectException(SanitizerException::class);
		class T { }
		$this->instance->stringValue( new T() );
	}
	
	/**
	 * @covers ::stringValue
	 */
	public function testStringValue() {
		$this->assertEquals('', $this->instance->stringValue(false));
		
		$this->assertTrue('1234' === $this->instance->stringValue(1234));

		$this->assertTrue('1.23' === $this->instance->stringValue(1.23));

		$this->assertEquals('abcd', $this->instance->stringValue('abcd'));

		$value = '<script>console.log(document.cookie);</script>';
		$result = $this->instance->stringValue($value);
		$this->assertEquals('&lt;script&gt;console.log(document.cookie);&lt;/script&gt;', $result);

		$result = $this->instance->stringValue($value, Sanitizer::DEFAULT_ALLOWED_HTML_TAGS);
		$this->assertEquals('console.log(document.cookie);', $result);

		$value = '&lt;script&gt;alert(document.cookie);&lt;/script&gt;';
		$result = $this->instance->stringValue($value);
		$this->assertEquals('&lt;script&gt;alert(document.cookie);&lt;/script&gt;', $result);

		$result = $this->instance->stringValue($value, Sanitizer::DEFAULT_ALLOWED_HTML_TAGS);
		$this->assertEquals('alert(document.cookie);', $result);

		$value = '%27%22 onclick=%22alert(document.cookie);%22 id=%22123';
		$result = $this->instance->stringValue($value);
		$this->assertEquals('&#39;&quot;onclick=&quot;alert(document.cookie);&quot;id=&quot;123', $result);

		$result = $this->instance->stringValue($value, Sanitizer::DEFAULT_ALLOWED_HTML_TAGS);
		$this->assertEquals('\'"onclick="alert(document.cookie);"id="123', $result);

		$value = '<b><i>bold</i></b>';
		$result = $this->instance->stringValue($value, '<b>');
		$this->assertEquals('<b>bold</b>', $result);
	}

	/**
	 * @covers ::validateEmail
	 */
	public function testValidateEmail() {
		// valid
		$result = $this->instance->validateEmail('test@mail.ca');
		$this->assertTrue($result);

		// invalid
		$result = $this->instance->validateEmail('test@mail.');
		$this->assertFalse($result);

		$result = $this->instance->validateEmail('test"@mail.ca');
		$this->assertFalse($result);
	}
	
	/**
	 * @covers  ::arrayOfInts
	 */
	public function testArrayOfInts() {
		// empty array
		$this->assertEquals([0], Sanitizer::arrayOfInts([]));

		// different types
		$test = ['1', 1, 'one', true];
		$result = Sanitizer::arrayOfInts($test);
		$expected = [1, 1, 0, 1];
		$this->assertEquals($expected, $result);

		// assoc array
		$test = [0 => '1', 'key2' => 1, 'two' => 'one', '123' => true];
		$result = Sanitizer::arrayOfInts($test);
		$expected = [0 => 1, 'key2' => 1, 'two' => 0, '123' => 1];
		$this->assertEquals($expected, $result);
	}
}
