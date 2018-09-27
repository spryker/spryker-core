<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Kernel;

use Codeception\Test\Unit;
use Spryker\Service\Kernel\Container;
use Spryker\Shared\Kernel\LocatorLocatorInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
 * @group Kernel
 * @group ContainerTest
 * Add your own group annotations below this line
 */
class ContainerTest extends Unit
{
    public const TEST_VALUE = 'foo';
    public const TEST_KEY = 'test.value';

    /**
     * @return void
     */
    public function testGetLocatorShouldReturnInstanceOfLocator()
    {
        $container = new Container();

        $this->assertInstanceOf(LocatorLocatorInterface::class, $container->getLocator());
    }
}
