<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel;

use SprykerEngine\Zed\Kernel\Container;

/**
 * @group SprykerEngine
 * @group Zed
 * @group Kernel
 * @group Container
 */
class ContainerTest extends \PHPUnit_Framework_TestCase
{

    public function testGetLocatorShouldReturnInstanceOFLocator()
    {
        $container = new Container();

        $this->assertInstanceOf('SprykerEngine\Zed\Kernel\Locator', $container->getLocator());
    }

}
