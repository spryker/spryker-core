<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Kernel\Backend;

use Codeception\Test\Unit;
use Spryker\Glue\Kernel\Backend\Container;
use Spryker\Glue\Kernel\Backend\Locator;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group Kernel
 * @group Backend
 * @group ContainerTest
 * Add your own group annotations below this line
 */
class ContainerTest extends Unit
{
    /**
     * @return void
     */
    public function testGetLocatorReturnsBackendLocator(): void
    {
        $container = new Container();

        $this->assertInstanceOf(Locator::class, $container->getLocator());
    }
}
