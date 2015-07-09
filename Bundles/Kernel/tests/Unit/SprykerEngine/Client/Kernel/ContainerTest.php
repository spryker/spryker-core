<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Client\Kernel;

use SprykerEngine\Client\Kernel\Container;

/**
 * @group SprykerEngine
 * @group Client
 * @group Kernel
 * @group Container
 */
class ContainerTest extends \PHPUnit_Framework_TestCase
{

    public function testGetLocatorShouldReturnInstanceOFLocator()
    {
        $container = new Container();

        $this->assertInstanceOf('SprykerEngine\Client\Kernel\Locator', $container->getLocator());
    }

}
