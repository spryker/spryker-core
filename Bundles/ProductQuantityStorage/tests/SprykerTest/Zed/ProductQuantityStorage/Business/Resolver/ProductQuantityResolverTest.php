<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductQuantityStorage\Business\Resolver;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Client\ProductQuantityStorage\Resolver\ProductQuantityResolver;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductQuantityStorage
 * @group Business
 * @group Resolver
 * @group ProductQuantityResolverTest
 * Add your own group annotations below this line
 */
class ProductQuantityResolverTest extends Unit
{
    /**
     * @return void
     */
    public function testGetNearestQuantity(): void
    {
        $productQuantityResolverMock = $this->createProductQuantityResolverMock();

        $this->assertInstanceOf(ProductQuantityResolver::class, $productQuantityResolverMock);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockBuilder
     */
    protected function createProductQuantityResolverMock(): MockObject
    {
        return $this->getMockBuilder(ProductQuantityResolver::class)->getMock();
    }
}
