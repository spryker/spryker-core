<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Client\Kernel;

use Spryker\Client\Kernel\Container;

/**
 * @group Spryker
 * @group Client
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

        $this->assertInstanceOf('Spryker\Client\Kernel\Locator', $container->getLocator());
    }

}
