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
use Spryker\Zed\Availability\Business\Model\AvailabilityHandlerInterface;
use Spryker\Zed\Availability\Business\Model\Sellable;
use Spryker\Zed\Availability\Business\Model\SellableInterface;
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
     * @dataProvider isProductSellableStoresDataProvider
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function testIsProductIsSellableWhenNeverOutOfStockShouldReturnIsSellable(StoreTransfer $storeTransfer): void
    {
        $availabilityRepositoryMock = $this->createAvailabilityRepositoryMock();
        $availabilityRepositoryMock->method('findProductConcreteAvailabilityBySkuAndStore')
            ->with(static::SKU_PRODUCT, $this->createStoreTransfer())
            ->willReturn(
                (new ProductConcreteAvailabilityTransfer())
                    ->setIsNeverOutOfStock(true)
            );

        $sellable = $this->createSellable($availabilityRepositoryMock);
        $isSellable = $sellable->isProductSellableForStore(static::SKU_PRODUCT, new Decimal(1), $storeTransfer);

        $this->assertTrue($isSellable);
    }

    /**
     * @return array
     */
    public function isProductSellableStoresDataProvider(): array
    {
        return [
            'store with ID' => [$this->createStoreTransfer()],
            'store without ID' => [(new StoreTransfer())->setName('DE')],
        ];
    }

    /**
     * @dataProvider reservedItemsAndExistingStockDataProvider
     *
     * @param \Spryker\DecimalObject\Decimal $existingAvailability
     *
     * @return void
     */
    public function testIsProductSellableWhenProductHaveInStockShouldReturnIsSellable(Decimal $existingAvailability): void
    {
        $storeTransfer = $this->createStoreTransfer();
        $availabilityRepositoryMock = $this->createAvailabilityRepositoryMock();
        $availabilityRepositoryMock->method('findProductConcreteAvailabilityBySkuAndStore')
            ->with(static::SKU_PRODUCT, $storeTransfer)
            ->willReturn(
                (new ProductConcreteAvailabilityTransfer())
                    ->setAvailability($existingAvailability)
                    ->setIsNeverOutOfStock(false)
            );

        $sellable = $this->createSellable($availabilityRepositoryMock);
        $isSellable = $sellable->isProductSellableForStore(static::SKU_PRODUCT, new Decimal(1), $storeTransfer);

        $this->assertTrue($isSellable);
    }

    /**
     * @return array
     */
    public function reservedItemsAndExistingStockDataProvider(): array
    {
        return [
            'int stock' => [new Decimal(10)],
            'float stock' => [new Decimal(9.8)],
            'float stock high precision' => [new Decimal(1.4444444444444)],
        ];
    }

    /**
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface|null $availabilityRepositoryMock
     * @param \Spryker\Zed\Availability\Business\Model\AvailabilityHandlerInterface|null $availabilityHandlerMock
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface|null $storeFacade
     *
     * @return \Spryker\Zed\Availability\Business\Model\SellableInterface
     */
    protected function createSellable(
        ?AvailabilityRepositoryInterface $availabilityRepositoryMock = null,
        ?AvailabilityHandlerInterface $availabilityHandlerMock = null,
        ?AvailabilityToStoreFacadeInterface $storeFacade = null
    ): SellableInterface {
        if ($availabilityRepositoryMock === null) {
            $availabilityRepositoryMock = $this->createAvailabilityRepositoryMock();
        }

        if ($availabilityHandlerMock === null) {
            $availabilityHandlerMock = $this->createAvailabilityHandlerMock();
        }

        if ($storeFacade === null) {
            $storeFacade = $this->createStoreFacade();
            $storeFacade->method('getCurrentStore')
                ->willReturn($this->createStoreTransfer());

            $storeFacade->method('getStoreByName')
                ->willReturn($this->createStoreTransfer());
        }

        return new Sellable($availabilityRepositoryMock, $availabilityHandlerMock, $storeFacade, []);
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function createStoreTransfer(): StoreTransfer
    {
        return (new StoreTransfer())->setName('DE')->setIdStore(1);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Business\Model\AvailabilityHandlerInterface
     */
    protected function createAvailabilityHandlerMock(): AvailabilityHandlerInterface
    {
        return $this->getMockBuilder(AvailabilityHandlerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface
     */
    protected function createStoreFacade(): AvailabilityToStoreFacadeInterface
    {
        return $this->getMockBuilder(AvailabilityToStoreFacadeInterface::class)->getMock();
    }

    /**
     * @return \Spryker\Zed\Availability\Persistence\AvailabilityRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createAvailabilityRepositoryMock(): AvailabilityRepositoryInterface
    {
        return $this->getMockBuilder(AvailabilityRepositoryInterface::class)->getMock();
    }
}
