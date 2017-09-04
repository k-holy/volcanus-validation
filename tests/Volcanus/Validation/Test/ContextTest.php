<?php
/**
 * PHP versions 5
 *
 * @copyright  2011 k-holy <k.holy74@gmail.com>
 * @author     k.holy74@gmail.com
 * @license    http://www.opensource.org/licenses/mit-license.php  The MIT License (MIT)
 */
namespace Volcanus\Validation\Test;

/**
 * ContextTest
 *
 * @author     k.holy74@gmail.com
 */
class ContextTest extends \PHPUnit\Framework\TestCase
{

	public function testArrayAccessForResultWhenNewInstanceWithArrayValues()
	{
		$array = array(
			'id'   => 1,
			'name' => 'foo',
		);
		$validation = new \Volcanus\Validation\Context($array);
		$this->assertEquals($validation->result['id'  ], $array['id']);
		$this->assertEquals($validation->result['name'], $array['name']);
	}

	public function testPropertyAccessForResultWhenNewInstanceWithObjectValues()
	{
		$object = new \stdClass();
		$object->id = 1;
		$object->name = 'foo';
		$validation = new \Volcanus\Validation\Context($object);
		$this->assertEquals($validation->result->id,  $object->id);
		$this->assertEquals($validation->result->name, $object->name);
	}

	public function testSetErrorAndUnsetError()
	{
		$validation = new \Volcanus\Validation\Context();
		$this->assertTrue($validation->isValid());
		$validation->setError('id', 'notFound');
		$this->assertTrue($validation->isError('id'));
		$this->assertFalse($validation->isValid());
		$validation->unsetError('id');
		$this->assertFalse($validation->isError('id'));
		$this->assertTrue($validation->isValid());
	}

	public function testSetErrorAndGetErrors()
	{
		$validation = new \Volcanus\Validation\Context();
		$validation->setError('id1', 'notFound');
		$validation->setError('id2', 'notFound');
		$errors = $validation->getErrors();
		$this->assertArrayHasKey('id1', $errors);
		$this->assertInstanceOf('\Volcanus\Validation\Error', $errors['id1']);
		$this->assertArrayHasKey('id2', $errors);
		$this->assertInstanceOf('\Volcanus\Validation\Error', $errors['id2']);
	}

	public function testSetErrorAndClearErrors()
	{
		$validation = new \Volcanus\Validation\Context();
		$this->assertTrue($validation->isValid());

		$validation->setError('id1', 'notFound');
		$this->assertTrue($validation->isError('id1'));
		$this->assertFalse($validation->isValid());

		$validation->setError('id2', 'notFound');
		$this->assertTrue($validation->isError('id2'));
		$this->assertFalse($validation->isValid());

		$validation->clearErrors();
		$this->assertFalse($validation->isError('id1'));
		$this->assertFalse($validation->isError('id2'));
		$this->assertTrue($validation->isValid());
	}

	public function testInitResult()
	{
		$values = array(
			'id' => 'a',
		);
		$validation = new \Volcanus\Validation\Context($values);
		$this->assertTrue($validation->isValid());

		$validation->setError('id', 'notFound');
		$this->assertTrue($validation->isError('id'));
		$this->assertFalse($validation->isValid());

		$validation->InitResult($values);
		$this->assertTrue($validation->isValid());
	}

	public function testRegisterChecker()
	{
		$validation = new \Volcanus\Validation\Context(array(
			'entry1' => 'abc',
			'entry2' => 'def',
		));
		$validation->registerChecker('abc', $this->abcChecker());
		$this->assertTrue($validation->check('entry1', 'abc'));
		$this->assertFalse($validation->check('entry2', 'abc'));
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testRaiseInvalidArgumentExceptionWhenCheckerIsNotCallable()
	{
		$validation = new \Volcanus\Validation\Context();
		$validation->registerChecker('abc', array('hoge', 'fuga'));
	}

	/**
	 * @expectedException \RuntimeException
	 */
	public function testRaiseRuntimeExceptionWhenUndefinedCheckerIsCalled()
	{
		$validation = new \Volcanus\Validation\Context();
		$validation->getChecker('def');
	}

	public function testInitChecker()
	{
		$validation = new \Volcanus\Validation\Context(array(
			'entry1' => 'abc',
			'entry2' => 'def',
		));
		$validation->initChecker(array(
			'abc' => $this->abcChecker(),
		));
		$this->assertTrue($validation->check('entry1', 'abc'));
		$this->assertFalse($validation->check('entry2', 'abc'));
	}

	/**
	 * @expectedException \RuntimeException
	 */
	public function testRaiseRuntimeExceptionWhenUndefinedCheckerIsCalledAfterInitChecker()
	{
		$validation = new \Volcanus\Validation\Context();
		$validation->registerChecker('abc', $this->abcChecker());
		$this->assertTrue(is_callable($validation->getChecker('abc')));
		$validation->initChecker();
		$validation->getChecker('abc');
	}

	public function testInitCheckerOnConstruct()
	{
		$validation = new \Volcanus\Validation\Context(array(
			'entry1' => 'abc',
			'entry2' => 'def',
		), array(
			'abc' => $this->abcChecker(),
		));
		$this->assertTrue($validation->check('entry1', 'abc'));
		$this->assertFalse($validation->check('entry2', 'abc'));
	}

	public function testGetDefaultChecker()
	{
		$validation = new \Volcanus\Validation\Context();
		$this->assertInstanceOf('\Volcanus\Validation\Checker\AlphaChecker',
			$validation->getDefaultChecker('alpha'));
	}

	public function testGetResult()
	{
		$validation = new \Volcanus\Validation\Context();
		$this->assertInstanceOf('\Volcanus\Validation\Result',
			$validation->getResult());
	}

	public function testIsValid()
	{
		$validation = new \Volcanus\Validation\Context(array(
			'id'   => 1,
			'name' => 'foo',
		));
		$validation->registerChecker('int'     , $this->intChecker());
		$validation->registerChecker('notEmpty', $this->notEmptyChecker());
		$this->assertTrue($validation->check('id'  , 'int'));
		$this->assertTrue($validation->check('name', 'notEmpty'));
		$this->assertTrue($validation->isValid());
	}

	public function testIsNotValid()
	{
		$validation = new \Volcanus\Validation\Context(array(
			'id'   => 'a',
			'name' => '',
		));
		$validation->registerChecker('int'     , $this->intChecker());
		$validation->registerChecker('notEmpty', $this->notEmptyChecker());
		$this->assertFalse($validation->check('id'  , 'int'));
		$this->assertFalse($validation->check('name', 'notEmpty'));
		$this->assertFalse($validation->isValid());
		$this->assertTrue($validation->isError('id'));
		$this->assertTrue($validation->isError('id', 'int'));
		$this->assertTrue($validation->isError('name'));
		$this->assertTrue($validation->isError('name', 'notEmpty'));
	}

	public function testIsError()
	{
		$validation = new \Volcanus\Validation\Context(array(
			'id' => 100,
		));
		$validation->registerChecker('int', $this->intChecker());
		$this->assertFalse($validation->check('id', 'int', array('min' => 1, 'max' => 10)));
		$this->assertFalse($validation->isValid());
		$this->assertTrue($validation->isError('id'));
		$this->assertTrue($validation->isError('id', 'int'));
		$this->assertTrue($validation->isError('id', 'int', array('min' => 1)));
		$this->assertTrue($validation->isError('id', 'int', array('min' => 1, 'max' => 10)));
	}

	public function testIsNotError()
	{
		$validation = new \Volcanus\Validation\Context(array(
			'id' => 1,
		));
		$validation->registerChecker('int', $this->intChecker());
		$validation->check('id', 'int', array(1, 10));
		$this->assertFalse($validation->isError('id'));
		$this->assertFalse($validation->isError('id', 'int'));
		$this->assertFalse($validation->isError('id', 'int', array('min' => 1)));
		$this->assertFalse($validation->isError('id', 'int', array('min' => 1, 'max' => 10)));
	}

	public function testGetChecker()
	{
		$validation = new \Volcanus\Validation\Context();
		$validation->registerChecker('abc', $this->abcChecker());
		$this->assertTrue(call_user_func($validation->getChecker('abc'), 'abc'));
		$this->assertFalse(call_user_func($validation->getChecker('abc'), 'def'));
	}

	public function testGetDefaultCheckerByMagicGetter()
	{
		$validation = new \Volcanus\Validation\Context();
		$this->assertEquals($validation->defaultChecker('alpha'),
			$validation->getDefaultChecker('alpha'));
	}

	public function testGetResultByMagicGetter()
	{
		$validation = new \Volcanus\Validation\Context();
		$this->assertSame($validation->result,
			$validation->getResult());
	}

	public function testGetCheckerByMagicGetter()
	{
		$validation = new \Volcanus\Validation\Context();
		$validation->registerChecker('abc', $this->abcChecker());
		$this->assertTrue(call_user_func($validation->checker('abc'), 'abc'));
		$this->assertFalse(call_user_func($validation->checker('abc'), 'def'));
	}

	public function testMessageProcessor()
	{
		$validation = new \Volcanus\Validation\Context();
		$validation->registerChecker('int', $this->intChecker());
		$validation->setMessageProcessor(function($name, $type, $options) {
			switch($type) {
			case 'int':
				$messages = array();
				$messages[] = sprintf('input numeric values');
				if (isset($options['min'])) {
					$messages[] = sprintf('from %d', $options['min']);
				}
				if (isset($options['max'])) {
					$messages[] = sprintf('to %d', $options['max']);
				}
				return implode(' ', $messages);
			}
		});

		$validation->InitResult(array('id' => 100));
		$validation->check('id', 'int', array('min' => 1, 'max' => 10));
		$this->assertContains('input numeric values from 1 to 10', $validation->getMessage('id'));

		$validation->InitResult(array('id' => 0));
		$validation->check('id', 'int', array('min' => 1));
		$this->assertContains('input numeric values from 1', $validation->getMessage('id'));

		$validation->InitResult(array('id' => 100));
		$validation->check('id', 'int', array('max' => 10));
		$this->assertContains('input numeric values to 10', $validation->getMessage('id'));
	}

	public function testCheckArrayValueWithAcceptArrayOption()
	{
		$validation = new \Volcanus\Validation\Context(array(
			'id'    => array(1, 2, 3),
			'id_ng' => array(1, 'a', 3),
		));
		$validation->registerChecker('int', $this->intChecker());
		$this->assertTrue($validation->check('id', 'int', array('acceptArray' => true)));
		$this->assertFalse($validation->check('id_ng', 'int', array('acceptArray' => true)));
	}

	public function testCheckNestedArrayValueWithAcceptArrayOption()
	{
		$validation = new \Volcanus\Validation\Context(array(
			'id' => array(
				array(
					array(1, 2, 3),
					array(4, 5, 6),
				),
				array(
					array(7, 8, 9),
					array(10, 11, 12),
				),
			),
			'id_ng' => array(
				array(
					array(1, 2, 3),
					array(4, 5, 6),
				),
				array(
					array(7, 8, 9),
					array(10, 'a', 12),
				),
			),
		));
		$validation->registerChecker('int', $this->intChecker());
		$this->assertTrue($validation->check('id', 'int', array('acceptArray' => true)));
		$this->assertFalse($validation->check('id_ng', 'int', array('acceptArray' => true)));
	}

	private function abcChecker()
	{
		return function ($value) {
			return (strcmp('abc', strtolower($value)) === 0);
		};
	}

	private function intChecker()
	{
		return function ($value, $options = array()) {
			if (!ctype_digit(strval($value)) ||
				strcmp(strval($value), sprintf('%d', $value)) !== 0
			) {
				return false;
			}
			if (isset($options['min']) && intval($value) < $options['min']) {
				return false;
			}
			if (isset($options['max']) && intval($value) > $options['max']) {
				return false;
			}
			return true;
		};
	}

	private function notEmptyChecker()
	{
		return function ($value) {
			return (isset($value) && strlen($value) !== 0);
		};
	}

}
