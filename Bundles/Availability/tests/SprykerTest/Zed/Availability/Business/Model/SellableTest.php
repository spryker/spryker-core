<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Availability\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\SellableItemBatchRequestTransfer;
use Generated\Shared\Transfer\SellableItemBatchResponseTransfer;
use Generated\Shared\Transfer\SellableItemRequestTransfer;
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

    public const SKU_PRODUCT_SECOND = 'sku-123-222';

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
     * @return void
     */
    public function testAreProductConcretesSellableForStoreWhenProductOutOfStockShouldReturnIsNotSellable(): void
    {
        // Arrange
        $storeTransfer = $this->createStoreTransfer();
        $availabilityRepositoryMock = $this->createAvailabilityRepositoryMock();
        $availabilityRepositoryMock->method('findProductConcreteAvailabilityBySkusAndStore')
            ->with([static::SKU_PRODUCT, static::SKU_PRODUCT_SECOND], $storeTransfer)
            ->willReturn(
                [
                    (new ProductConcreteAvailabilityTransfer())
                        ->setSku(static::SKU_PRODUCT)
                        ->setAvailability(6)
                        ->setIsNeverOutOfStock(false),
                    (new ProductConcreteAvailabilityTransfer())
                        ->setSku(static::SKU_PRODUCT_SECOND)
                        ->setAvailability(6)
                        ->setIsNeverOutOfStock(false),
                ]
            );
        $batchRequestData = [
            [
                'sku' => static::SKU_PRODUCT,
                'quantity' => 6,
            ],
            [
                'sku' => static::SKU_PRODUCT_SECOND,
                'quantity' => 7,
            ],
        ];

        $sellableItemBatchRequestTransfer = $this->createSellableItemBatchRequestTransfer($storeTransfer);
        $sellableItemBatchRequestTransfer = $this->addSellableItemRequestTransfersFromArray(
            $sellableItemBatchRequestTransfer,
            $batchRequestData
        );
        $sellableItemBatchResponseTransfer = $this->createSellableItemBatchResponseTransfer();

        $sellable = $this->createSellable($availabilityRepositoryMock);

        //Act
        $sellableItemBatchResponseTransfer = $sellable->areProductConcretesSellableForStore(
            $sellableItemBatchRequestTransfer,
            $sellableItemBatchResponseTransfer
        );

        //Assert
        $sellableItemBatchResponseTransferMap = $this->getSellableItemResponseTransfersMapBySku($sellableItemBatchResponseTransfer);

        $this->assertSame(2, count($sellableItemBatchResponseTransfer->getSellableItemResponses()));
        $this->assertTrue($sellableItemBatchResponseTransferMap[static::SKU_PRODUCT]->getIsSellable());

        $this->assertFalse($sellableItemBatchResponseTransferMap[static::SKU_PRODUCT_SECOND]->getIsSellable());
    }

    /**
     * @return void
     */
    public function testAreProductConcretesSellableForStoreWhenProductsInStockShouldReturnIsSellable(): void
    {
        // Arrange
        $storeTransfer = $this->createStoreTransfer();
        $availabilityRepositoryMock = $this->createAvailabilityRepositoryMock();
        $availabilityRepositoryMock->method('findProductConcreteAvailabilityBySkusAndStore')
            ->with([static::SKU_PRODUCT, static::SKU_PRODUCT_SECOND], $storeTransfer)
            ->willReturn(
                [
                    (new ProductConcreteAvailabilityTransfer())
                        ->setSku(static::SKU_PRODUCT)
                        ->setAvailability(6)
                        ->setIsNeverOutOfStock(false),
                    (new ProductConcreteAvailabilityTransfer())
                        ->setSku(static::SKU_PRODUCT_SECOND)
                        ->setAvailability(6)
                        ->setIsNeverOutOfStock(false),
                ]
            );
        $batchRequestData = [
            [
                'sku' => static::SKU_PRODUCT,
                'quantity' => 5,
            ],
            [
                'sku' => static::SKU_PRODUCT_SECOND,
                'quantity' => 5,
            ],
        ];

        $sellableItemBatchRequestTransfer = $this->createSellableItemBatchRequestTransfer($storeTransfer);
        $sellableItemBatchRequestTransfer = $this->addSellableItemRequestTransfersFromArray(
            $sellableItemBatchRequestTransfer,
            $batchRequestData
        );
        $sellableItemBatchResponseTransfer = $this->createSellableItemBatchResponseTransfer();

        $sellable = $this->createSellable($availabilityRepositoryMock);

        //Act
        $sellableItemBatchResponseTransfer = $sellable->areProductConcretesSellableForStore(
            $sellableItemBatchRequestTransfer,
            $sellableItemBatchResponseTransfer
        );

        //Assert
        $sellableItemBatchResponseTransferMap = $this->getSellableItemResponseTransfersMapBySku($sellableItemBatchResponseTransfer);

        $this->assertSame(2, count($sellableItemBatchResponseTransfer->getSellableItemResponses()));
        $this->assertTrue($sellableItemBatchResponseTransferMap[static::SKU_PRODUCT]->getIsSellable());

        $this->assertTrue($sellableItemBatchResponseTransferMap[static::SKU_PRODUCT_SECOND]->getIsSellable());
    }

    /**
     * @return void
     */
    public function testAreProductConcretesSellableForStoreWhenProductsAreNeverOutOfStockShouldReturnIsSellable(): void
    {
        // Arrange
        $storeTransfer = $this->createStoreTransfer();
        $availabilityRepositoryMock = $this->createAvailabilityRepositoryMock();
        $availabilityRepositoryMock->method('findProductConcreteAvailabilityBySkusAndStore')
            ->with([static::SKU_PRODUCT, static::SKU_PRODUCT_SECOND], $storeTransfer)
            ->willReturn(
                [
                    (new ProductConcreteAvailabilityTransfer())
                        ->setSku(static::SKU_PRODUCT)
                        ->setIsNeverOutOfStock(true),
                    (new ProductConcreteAvailabilityTransfer())
                        ->setSku(static::SKU_PRODUCT_SECOND)
                        ->setIsNeverOutOfStock(true),
                ]
            );
        $batchRequestData = [
            [
                'sku' => static::SKU_PRODUCT,
                'quantity' => 5,
            ],
            [
                'sku' => static::SKU_PRODUCT_SECOND,
                'quantity' => 5,
            ],
        ];

        $sellableItemBatchRequestTransfer = $this->createSellableItemBatchRequestTransfer($storeTransfer);
        $sellableItemBatchRequestTransfer = $this->addSellableItemRequestTransfersFromArray(
            $sellableItemBatchRequestTransfer,
            $batchRequestData
        );
        $sellableItemBatchResponseTransfer = $this->createSellableItemBatchResponseTransfer();

        $sellable = $this->createSellable($availabilityRepositoryMock);

        //Act
        $sellableItemBatchResponseTransfer = $sellable->areProductConcretesSellableForStore(
            $sellableItemBatchRequestTransfer,
            $sellableItemBatchResponseTransfer
        );

        //Assert
        $sellableItemBatchResponseTransferMap = $this->getSellableItemResponseTransfersMapBySku($sellableItemBatchResponseTransfer);

        $this->assertSame(2, count($sellableItemBatchResponseTransfer->getSellableItemResponses()));
        $this->assertTrue($sellableItemBatchResponseTransferMap[static::SKU_PRODUCT]->getIsSellable());

        $this->assertTrue($sellableItemBatchResponseTransferMap[static::SKU_PRODUCT_SECOND]->getIsSellable());
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
     * @param \Generated\Shared\Transfer\SellableItemBatchResponseTransfer $sellableItemBatchResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemResponseTransfer[]
     */
    protected function getSellableItemResponseTransfersMapBySku(SellableItemBatchResponseTransfer $sellableItemBatchResponseTransfer): array
    {
        $sellableItemBatchResponseTransferMap = [];

        foreach ($sellableItemBatchResponseTransfer->getSellableItemResponses() as $sellableItemResponseTransfer) {
            $sellableItemBatchResponseTransferMap[$sellableItemResponseTransfer->getSku()] = $sellableItemResponseTransfer;
        }

        return $sellableItemBatchResponseTransferMap;
    }

    /**
     * @return \Generated\Shared\Transfer\SellableItemBatchResponseTransfer
     */
    protected function createSellableItemBatchResponseTransfer(): SellableItemBatchResponseTransfer
    {
        return new SellableItemBatchResponseTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemBatchRequestTransfer
     */
    protected function createSellableItemBatchRequestTransfer(StoreTransfer $storeTransfer): SellableItemBatchRequestTransfer
    {
        $sellableItemBatchRequestTransfer = new SellableItemBatchRequestTransfer();
        $sellableItemBatchRequestTransfer->setStore($storeTransfer);

        return $sellableItemBatchRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemBatchRequestTransfer $sellableItemBatchRequestTransfer
     * @param mixed[] $batchRequestData
     *
     * @return \Generated\Shared\Transfer\SellableItemBatchRequestTransfer
     */
    protected function addSellableItemRequestTransfersFromArray(
        SellableItemBatchRequestTransfer $sellableItemBatchRequestTransfer,
        array $batchRequestData
    ): SellableItemBatchRequestTransfer {
        foreach ($batchRequestData as $sellableItemRequest) {
            $sellableItemRequestTransfer = new SellableItemRequestTransfer();
            $sellableItemRequestTransfer->fromArray($sellableItemRequest, true);
            $sellableItemBatchRequestTransfer->addSellableItemRequest($sellableItemRequestTransfer);
        }

        return $sellableItemBatchRequestTransfer;
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

        return new Sellable($availabilityRepositoryMock, $availabilityHandlerMock, $storeFacade, [], []);
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
