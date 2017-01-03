<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Client\Kernel;

use PHPUnit_Framework_TestCase;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Kernel\Locator;

/**
 * @group Unit
 * @group Spryker
 * @group Client
 * @group Kernel
 * @group ContainerTest
 */
class ContainerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testGetLocatorShouldReturnInstanceOFLocator()
    {
        $container = new Container();

        $this->assertInstanceOf(Locator::class, $container->getLocator());
    }

}
