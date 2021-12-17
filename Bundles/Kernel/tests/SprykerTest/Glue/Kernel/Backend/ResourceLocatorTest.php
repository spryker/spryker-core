<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Kernel\Backend;

use Codeception\Test\Unit;
use Spryker\Glue\Kernel\AbstractRestResource;
use Spryker\Glue\Kernel\Backend\AbstractRestResource as BackendAbstractRestResource;
use Spryker\Glue\Kernel\Backend\ResourceLocator;
use Spryker\Glue\Kernel\ClassResolver\RestResource\RestResourceNotFoundException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group Kernel
 * @group Backend
 * @group ResourceLocatorTest
 * Add your own group annotations below this line
 */
class ResourceLocatorTest extends Unit
{
    /**
     * @return void
     */
    public function testMissingResourceLocatorThrowsException(): void
    {
        $this->expectException(RestResourceNotFoundException::class);

        $locator = new ResourceLocator();
        $locator->locate(static::class);
    }

    /**
     * @return void
     */
    public function testInvalidResourceLocatorThrowsException(): void
    {
        $resourceLocatorMock = $this->createMock(ResourceLocator::class);
        $resourceLocatorMock
            ->expects($this->once())
            ->method('locate')
            ->willReturn(
                $this->createMock(BackendAbstractRestResource::class),
            );
        $resourceLocatorMock->locate(AbstractRestResource::class);
    }

    /**
     * @return void
     */
    public function testResourceLocatorShouldReturnResolvedResource(): void
    {
        $resourceLocatorMock = $this->createMock(ResourceLocator::class);
        $resourceLocatorMock
            ->expects($this->once())
            ->method('locate')
            ->willReturn(
                $this->createMock(BackendAbstractRestResource::class),
            );
        $resourceLocatorMock->locate(BackendAbstractRestResource::class);
    }
}
