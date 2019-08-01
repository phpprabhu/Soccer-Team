<?php

namespace App\Tests\Unit\Service;

use Mockery;

/**
 * Base class for test cases.
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase {

    /**
     * The arguments mocked for the class under test.
     *
     * @var \Mockery\MockInterface[]
     */
    protected $classUnderTestConstructorArgs = array();

    /**
     * The class under test name.
     *
     * @var string
     */
    protected $classUnderTestName;

    /**
     * Sets up the fixture.
     *
     * @throws \RuntimeException If the class under test name is wrong.
     */
    protected function setUp() : void {
        parent::setUp();
        if ($this->classUnderTestName !== null) {
            if (class_exists($this->classUnderTestName) !== true) {
                throw new \RuntimeException('The class name "' . $this->classUnderTestName . '" under test is wrong.');
            }

            $this->classUnderTestConstructorArgs = $this->getMockedClassUnderTestConstructorArgs();
        }
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     *
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        foreach ($this->getTearDownProperties() as $prop) {
            $prop->setValue($this, null);
        }

        Mockery::close();
        parent::tearDown();
    }

    /**
     * Get the arguments mocked to instantiate the workflow class.
     *
     * @return \Mockery\MockInterface[]
     */
    private function getMockedClassUnderTestConstructorArgs() {
        $args = array();

        $reflection = new \ReflectionClass($this->classUnderTestName);
        $constructor = $reflection->getConstructor();
        if ($constructor !== null) {
            $parameters = $constructor->getParameters();
            foreach ($parameters as $param) {
                $paramClass = $param->getClass();
                $args[$param->getName()] = $paramClass === null ? null : Mockery::mock($paramClass->name);
            }
        }

        return $args;
    }

    /**
     * Get an instance of the class under test with dependencies mocked.
     *
     * @param array $methodsToMock Array of methods to mock.
     * @return object, \Mockery\MockInterface
     */
    protected function getInstanceOfClassUnderTest(array $methodsToMock = null) {
        if ($methodsToMock !== null) {
            $methodsToMock = '[' . implode(',', $methodsToMock) . ']';
            return Mockery::mock($this->classUnderTestName . $methodsToMock, array_values($this->classUnderTestConstructorArgs));
        } else {
            $reflection = new \ReflectionClass($this->classUnderTestName);
            return $reflection->newInstanceArgs($this->classUnderTestConstructorArgs);
        }
    }

    /**
     * Returns an array of ReflectionProperty objects for tear down.
     *
     * @return array
     */
    private function getTearDownProperties() {
        static $cache = array();

        $class = get_class($this);
        if (!isset($cache[$class])) {
            $cache[$class] = array();
            $refl = new \ReflectionClass($class);
            $filter = \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE;
            foreach ($refl->getProperties($filter) as $prop) {
                if (0 !== strpos($prop->getDeclaringClass()->getName(), 'PHPUnit_')) {
                    $prop->setAccessible(true);
                    $cache[$class][] = $prop;
                }
            }
        }

        return $cache[$class];
    }
}
