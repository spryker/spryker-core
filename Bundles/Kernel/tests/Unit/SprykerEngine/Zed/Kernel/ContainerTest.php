<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel;

use Spryker\Zed\Kernel\Container;

/**
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group Container
 */
class ContainerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testGetLocatorShouldReturnInstanceOFLocator()
    {
        $container = new Container();

        $this->assertInstanceOf('Spryker\Zed\Kernel\Locator', $container->getLocator());
    }

}
