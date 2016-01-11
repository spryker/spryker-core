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
        $this->setExpectedException('\Exception');

        new MissingPropertyLocator();
    }

    /**
     * @return void
     */
    public function testCanCreateShouldThrowException()
    {
        $this->setExpectedException('\Exception');

        new MissingPropertyLocator();
    }

}
