<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Kernel;

use Codeception\Test\Unit;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Kernel\Locator;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Kernel
 * @group ContainerTest
 * Add your own group annotations below this line
 */
class ContainerTest extends Unit
{
    /**
     * @return void
     */
    public function testGetLocatorShouldReturnInstanceOfLocator()
    {
        $container = new Container();

        $this->assertInstanceOf(Locator::class, $container->getLocator());
    }
}
