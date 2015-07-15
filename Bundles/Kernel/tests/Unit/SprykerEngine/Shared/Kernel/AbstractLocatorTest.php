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

    public function testCreateInstanceWithoutAFactoryClassNamePatternInConstructShouldThrowException()
    {
        $this->setExpectedException('SprykerEngine\Shared\Kernel\Locator\LocatorException');

        $locator = new Locator();
    }

    public function testCreateInstance()
    {
        $locator = new Locator('foo');

        $this->assertInstanceOf('SprykerEngine\Shared\Kernel\AbstractLocator', $locator);
    }

    public function testLocateShouldThrowExceptionIfFactoryCanNotBeFound()
    {
        $locator = new Locator('foo');
        $this->setExpectedException('SprykerEngine\Shared\Kernel\Locator\LocatorException');

        $locator->locate('Bar', \SprykerEngine\Zed\Kernel\Locator::getInstance());
    }

    public function testLocateShouldReturnInstanceIfFactoryCanBeFound()
    {
        $locator = new Locator('\\Unit\\{{namespace}}\\Shared\\{{bundle}}\\Fixtures\\Factory');
        $instance = $locator->locate('Kernel', \SprykerEngine\Zed\Kernel\Locator::getInstance());

        $this->assertInstanceOf('Unit\SprykerEngine\Shared\Kernel\Fixtures\Factory', $instance);
    }

}
