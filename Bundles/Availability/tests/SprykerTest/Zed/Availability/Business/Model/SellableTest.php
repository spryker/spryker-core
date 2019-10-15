<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Availability\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Availability\Business\Model\Sellable;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;

/**
 * Auto-generated group annotations
 *
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
    public const SKU_PRODUCT = 'sku-123-321';

    /**
     * @return void
     */
    public function testIsProductIsSellableWhenNeverOutOfStockShouldReturnIsSellable()
    {
        $stockFacadeMock = $this->createStockFacadeMock();
        $stockFacadeMock->method('isNeverOutOfStockForStore')
            ->with(self::SKU_PRODUCT)
            ->willReturn(true);

        $sellable = $this->createSellable(null, $stockFacadeMock);
        $isSellable = $sellable->isProductSellable(self::SKU_PRODUCT, new Decimal(1));

        $this->assertTrue($isSellable);
    }

    /**
     * @dataProvider provideReservedItemsAndExistingStock
     *
     * @param \Spryker\DecimalObject\Decimal $reservedItems
     * @param \Spryker\DecimalObject\Decimal $existingStock
     *
     * @return void
     */
    public function testIsProductSellableWhenProductHaveInStockShouldReturnIsSellable(Decimal $reservedItems, Decimal $existingStock): void
    {
        $stockFacadeMock = $this->createStockFacadeMock();
        $stockFacadeMock->method('isNeverOutOfStockForStore')
            ->with(self::SKU_PRODUCT)
            ->willReturn(false);

        $stockFacadeMock->method('calculateProductStockForStore')
            ->with(self::SKU_PRODUCT)
            ->willReturn($existingStock);

        $omsFacadeMock = $this->createOmsFacadeMock();
        $omsFacadeMock->method('sumReservedProductQuantitiesForSku')
            ->with(self::SKU_PRODUCT)
            ->willReturn($reservedItems);

        $sellable = $this->createSellable($omsFacadeMock, $stockFacadeMock);
        $isSellable = $sellable->isProductSellable(self::SKU_PRODUCT, new Decimal(1));

        $this->assertTrue($isSellable);
    }

    /**
     * @return array
     */
    public function provideReservedItemsAndExistingStock(): array
    {
        return [
            'int stock' => [new Decimal(5), new Decimal(10)],
            'float stock' => [new Decimal(5.5), new Decimal(9.8)],
            'float stock high precision' => [new Decimal(1.4444444444444), new Decimal(2.5)],
            'mixed type stock' => [new Decimal(5), new Decimal(9.8)],
        ];
    }

    /**
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsInterface|null $omsFacadeMock
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface|null $stockFacadeMock
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface|null $storeFacade
     *
     * @return \Spryker\Zed\Availability\Business\Model\Sellable
     */
    protected function createSellable(
        ?AvailabilityToOmsInterface $omsFacadeMock = null,
        ?AvailabilityToStockInterface $stockFacadeMock = null,
        ?AvailabilityToStoreFacadeInterface $storeFacade = null
    ) {

        if ($omsFacadeMock === null) {
            $omsFacadeMock = $this->createOmsFacadeMock();
        }

        if ($stockFacadeMock === null) {
            $stockFacadeMock = $this->createStockFacadeMock();
        }

        if ($storeFacade === null) {
            $storeFacade = $this->createStoreFacade();
            $storeFacade->method('getCurrentStore')
                ->willReturn($this->createStoreTransfer());
        }

        return new Sellable($omsFacadeMock, $stockFacadeMock, $storeFacade);
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function createStoreTransfer()
    {
        return (new StoreTransfer())->setName('DE');
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface
     */
    protected function createStockFacadeMock()
    {
        return $this->getMockBuilder(AvailabilityToStockInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsInterface
     */
    protected function createOmsFacadeMock()
    {
        return $this->getMockBuilder(AvailabilityToOmsInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface
     */
    protected function createStoreFacade()
    {
        return $this->getMockBuilder(AvailabilityToStoreFacadeInterface::class)
            ->getMock();
    }
}
