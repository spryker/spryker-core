<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Availability\Business\Model;

use Codeception\Test\Unit;
use Spryker\Zed\Availability\Business\Model\Sellable;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Availability
 * @group Business
 * @group Model
 * @group SellableTest
 * Add your own group annotations below this line
 */
class SellableTest extends Unit
{

    const SKU_PRODUCT = 'sku-123-321';

    /**
     * @return void
     */
    public function testIsProductIsSellableWhenNeverOutOfStockShouldReturnIsSellable()
    {
        $stockFacadeMock = $this->createStockFacadeMock();
        $stockFacadeMock->method('isNeverOutOfStock')
            ->with(self::SKU_PRODUCT)
            ->willReturn(true);

        $sellable = $this->createSellable(null, $stockFacadeMock);
        $isSellable = $sellable->isProductSellable(self::SKU_PRODUCT, 1);

        $this->assertTrue($isSellable);
    }

    /**
     * @return void
     */
    public function testIsProductSellableWhenProductHaveInStockShouldReturnIsSellable()
    {
        $reservedItems = 5;
        $existingStock = 10;

        $stockFacadeMock = $this->createStockFacadeMock();
        $stockFacadeMock->method('isNeverOutOfStock')
            ->with(self::SKU_PRODUCT)
            ->willReturn(false);

        $stockFacadeMock->method('calculateStockForProduct')
            ->with(self::SKU_PRODUCT)
            ->willReturn($existingStock);

        $omsFacadeMock = $this->createOmsFacadeMock();
        $omsFacadeMock->method('sumReservedProductQuantitiesForSku')
            ->with(self::SKU_PRODUCT)
            ->willReturn($reservedItems);

        $sellable = $this->createSellable($omsFacadeMock, $stockFacadeMock);
        $isSellable = $sellable->isProductSellable(self::SKU_PRODUCT, 1);

        $this->assertTrue($isSellable);
    }

    /**
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsInterface|null $omsFacadeMock
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface|null $stockFacadeMock
     *
     * @return \Spryker\Zed\Availability\Business\Model\Sellable
     */
    protected function createSellable(
        AvailabilityToOmsInterface $omsFacadeMock = null,
        AvailabilityToStockInterface $stockFacadeMock = null
    ) {

        if ($omsFacadeMock === null) {
            $omsFacadeMock = $this->createOmsFacadeMock();
        }

        if ($stockFacadeMock === null) {
            $stockFacadeMock = $this->createStockFacadeMock();
        }

        return new Sellable($omsFacadeMock, $stockFacadeMock);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface
     */
    protected function createStockFacadeMock()
    {
        return $this->getMockBuilder(AvailabilityToStockInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsInterface
     */
    protected function createOmsFacadeMock()
    {
        return $this->getMockBuilder(AvailabilityToOmsInterface::class)
            ->getMock();
    }

}
