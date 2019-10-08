<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Availability\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Availability\Business\Model\Sellable;
use Spryker\Zed\Availability\Business\Model\SellableInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface;

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
        $storeTransfer = $this->createStoreTransfer();
        $availabilityRepositoryMock = $this->createAvailabilityRepositoryMock();
        $availabilityRepositoryMock->method('findProductConcreteAvailabilityBySkuAndStore')
            ->with(static::SKU_PRODUCT, $storeTransfer)
            ->willReturn(
                (new ProductConcreteAvailabilityTransfer())
                    ->setIsNeverOutOfStock(true)
            );

        $sellable = $this->createSellable(null, null, null, $availabilityRepositoryMock);
        $isSellable = $sellable->isProductSellableForStore(static::SKU_PRODUCT, new Decimal(1), $storeTransfer);

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
        $storeTransfer = $this->createStoreTransfer();
        $availabilityRepositoryMock = $this->createAvailabilityRepositoryMock();
        $availabilityRepositoryMock->method('findProductConcreteAvailabilityBySkuAndStore')
            ->with(static::SKU_PRODUCT, $storeTransfer)
            ->willReturn(
                (new ProductConcreteAvailabilityTransfer())
                    ->setAvailability($existingStock)
                    ->setIsNeverOutOfStock(false)
            );

        $sellable = $this->createSellable(null, null, null, $availabilityRepositoryMock);
        $isSellable = $sellable->isProductSellableForStore(static::SKU_PRODUCT, new Decimal(1), $storeTransfer);

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
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsFacadeInterface|null $omsFacadeMock
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface|null $stockFacadeMock
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface|null $storeFacade
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface|null $availabilityRepositoryMock
     *
     * @return \Spryker\Zed\Availability\Business\Model\SellableInterface
     */
    protected function createSellable(
        ?AvailabilityToOmsFacadeInterface $omsFacadeMock = null,
        ?AvailabilityToStockFacadeInterface $stockFacadeMock = null,
        ?AvailabilityToStoreFacadeInterface $storeFacade = null,
        ?AvailabilityRepositoryInterface $availabilityRepositoryMock = null
    ): SellableInterface {
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

        if ($availabilityRepositoryMock === null) {
            $availabilityRepositoryMock = $this->createAvailabilityRepositoryMock();
        }

        return new Sellable($omsFacadeMock, $stockFacadeMock, $storeFacade, $availabilityRepositoryMock);
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function createStoreTransfer()
    {
        return (new StoreTransfer())->setName('DE')->setIdStore(1);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface
     */
    protected function createStockFacadeMock()
    {
        return $this->getMockBuilder(AvailabilityToStockFacadeInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsFacadeInterface
     */
    protected function createOmsFacadeMock()
    {
        return $this->getMockBuilder(AvailabilityToOmsFacadeInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface
     */
    protected function createStoreFacade()
    {
        return $this->getMockBuilder(AvailabilityToStoreFacadeInterface::class)->getMock();
    }

    /**
     * @return \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createAvailabilityRepositoryMock()
    {
        return $this->getMockBuilder(AvailabilityRepositoryInterface::class)->getMock();
    }
}
