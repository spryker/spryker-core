<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Shared\Kernel;

use Unit\SprykerEngine\Shared\Kernel\Fixtures\Locator;
use Unit\SprykerEngine\Shared\Kernel\Fixtures\MissingPropertyLocator;

/**
 * @group Kernel
 * @group Locator
 * @group AbstractLocator
 */
class AbstractLocatorTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateInstanceWithoutAFactoryClassNamePatternPropertyShouldThrowException()
    {
        $this->setExpectedException('SprykerEngine\Shared\Kernel\Locator\LocatorException');

        $locator = new MissingPropertyLocator();
    }

    public function testCreateInstance()
    {
        $locator = new Locator('foo');

        $this->assertInstanceOf('SprykerEngine\Shared\Kernel\AbstractLocator', $locator);
    }

}
