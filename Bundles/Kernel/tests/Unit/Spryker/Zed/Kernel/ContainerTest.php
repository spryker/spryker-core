<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Kernel;

use Spryker\Zed\Kernel\Container;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group ContainerTest
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
