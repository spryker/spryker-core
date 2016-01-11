<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Shared\Kernel;

use Unit\Spryker\Shared\Kernel\Fixtures\MissingPropertyLocator;

/**
 * @group Kernel
 * @group Locator
 * @group AbstractLocator
 */
class AbstractLocatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCreateInstanceShouldThrowExceptionIfApplicationNotDefined()
    {
        $this->setExpectedException('Spryker\Shared\Kernel\Locator\LocatorException');

        new MissingPropertyLocator();
    }

    /**
     * @return void
     */
    public function testCanCreateShouldThrowException()
    {
        $this->setExpectedException('Spryker\Shared\Kernel\Locator\LocatorException');

        new MissingPropertyLocator();
    }

}
