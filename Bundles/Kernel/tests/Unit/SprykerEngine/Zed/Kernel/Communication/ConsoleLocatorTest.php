<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Communication;

use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Kernel\Communication\ConsoleLocator;

/**
 * @group Kernel
 * @group Locator
 * @group ConsoleLocator
 */
class ConsoleLocatorTest extends \PHPUnit_Framework_TestCase
{

    const BUNDLE_NAME = 'Kernel';
    const WRONG_BUNDLE_NAME = 'not existing bundle name';

    /**
     * @return array
     */
    public function classNameProvider()
    {
        return [
            ['Foo', 'Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\ConsoleLocator\Console\Foo'],
            ['FooBar', 'Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\ConsoleLocator\Console\Foo\Bar'],
        ];
    }

    public function testLocateShouldThrowExceptionIfFactoryCanNotBeFound()
    {
        $this->setExpectedException('\SprykerEngine\Shared\Kernel\Locator\LocatorException');

        $locator = new ConsoleLocator(
            '\\Unit\\SprykerEngine\\Zed\\{{bundle}}{{store}}\\Communication\\Fixtures\\Factory'
        );
        $locator->locate(self::WRONG_BUNDLE_NAME, Locator::getInstance());
    }

    /**
     * @dataProvider classNameProvider
     *
     * @param $className
     * @param $fullyQualifiedClassName
     */
    public function testLocateShouldReturnClassInstanceIfItCanBeLocated($className, $fullyQualifiedClassName)
    {
        $locator = new ConsoleLocator(
            '\\Unit\\SprykerEngine\\Zed\\{{bundle}}{{store}}\\Communication\\Fixtures\\ConsoleLocator\\Factory'
        );
        $locatedPlugin = $locator->locate('Kernel', Locator::getInstance(), $className);

        $this->assertInstanceOf($fullyQualifiedClassName, $locatedPlugin);
    }

}
