<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferStorage\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferStoreTableMap;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use SprykerTest\Zed\ProductOfferStorage\ProductOfferStorageBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferStorage
 * @group Business
 * @group Facade
 * @group WriteCollectionByProductOfferStoreEventsTest
 * Add your own group annotations below this line
 */
class WriteCollectionByProductOfferStoreEventsTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var \SprykerTest\Zed\ProductOfferStorage\ProductOfferStorageBusinessTester
     */
    protected ProductOfferStorageBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $this->tester->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->tester->clearProductOfferData();
    }

    /**
     * @return void
     */
    public function testCreatesNewProductOffersStorageEntity(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOfferWithStore();
        $eventEntityTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
                SpyProductOfferStoreTableMap::COL_FK_STORE => $productOfferTransfer->getStores()[0]->getIdStore(),
            ]),
        ];

        // Act
        $this->tester->getFacade()->writeCollectionByProductOfferStoreEvents($eventEntityTransfers);

        // Assert
        $this->tester->assertProductOfferStorageEntities($this->tester->getProductOfferStorageEntities(), $productOfferTransfer);
    }

    /**
     * @return void
     */
    public function testCreatesNewProductOffersStorageEntityForAssignedStoreAndRemovesForNotAssigned(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOfferWithStore();
        $this->tester->haveProductOfferStorage(
            (new ProductOfferStorageTransfer())->setProductOfferReference($productOfferTransfer->getProductOfferReference()),
            (new StoreTransfer())->setName(static::STORE_NAME_DE),
        );
        $eventEntityTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
                SpyProductOfferStoreTableMap::COL_FK_STORE => $productOfferTransfer->getStores()[0]->getIdStore(),
            ]),
        ];

        // Act
        $this->tester->getFacade()->writeCollectionByProductOfferStoreEvents($eventEntityTransfers);

        // Assert
        $this->tester->assertProductOfferStorageEntities($this->tester->getProductOfferStorageEntities(), $productOfferTransfer);
    }

    /**
     * @dataProvider \SprykerTest\Zed\ProductOfferStorage\ProductOfferStorageBusinessTester::getNotSellableProductOfferDataProvider
     *
     * @param array<string, mixed> $productOfferData
     * @param array<string, mixed> $productData
     *
     * @return void
     */
    public function testDoesNotCreateNewNotSellableProductOffersStorageEntity(
        array $productOfferData,
        array $productData
    ): void {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOfferWithStore($productOfferData, $productData);
        $eventEntityTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
                SpyProductOfferStoreTableMap::COL_FK_STORE => $productOfferTransfer->getStores()[0]->getIdStore(),
            ]),
        ];

        // Act
        $this->tester->getFacade()->writeCollectionByProductOfferStoreEvents($eventEntityTransfers);

        // Assert
        $this->assertCount(0, $this->tester->getProductOfferStorageEntities());
    }

    /**
     * @dataProvider \SprykerTest\Zed\ProductOfferStorage\ProductOfferStorageBusinessTester::getNotSellableProductOfferDataProvider
     *
     * @param array<string, mixed> $productOfferData
     * @param array<string, mixed> $productData
     *
     * @return void
     */
    public function testDeletesNotSellableProductOffersStorageEntity(
        array $productOfferData,
        array $productData
    ): void {
        // Arrange
        $sellableProductOfferTransfer = $this->tester->haveProductOfferWithStore($this->tester->getSellableProductOfferData());
        $notSellableProductOfferTransfer = $this->tester->haveProductOfferWithStore($productOfferData, $productData);
        $eventEntityTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER => $sellableProductOfferTransfer->getIdProductOffer(),
                SpyProductOfferStoreTableMap::COL_FK_STORE => $sellableProductOfferTransfer->getStores()[0]->getIdStore(),
            ]),
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER => $notSellableProductOfferTransfer->getIdProductOffer(),
                SpyProductOfferStoreTableMap::COL_FK_STORE => $notSellableProductOfferTransfer->getStores()[0]->getIdStore(),
            ]),
        ];
        $this->tester->haveProductOfferStorage(
            (new ProductOfferStorageTransfer())->setProductOfferReference($sellableProductOfferTransfer->getProductOfferReference()),
            (new StoreTransfer())->setName($sellableProductOfferTransfer->getStores()[0]->getNameOrFail()),
        );
        $this->tester->haveProductOfferStorage(
            (new ProductOfferStorageTransfer())->setProductOfferReference($notSellableProductOfferTransfer->getProductOfferReference()),
            (new StoreTransfer())->setName($notSellableProductOfferTransfer->getStores()[0]->getNameOrFail()),
        );

        // Act
        $this->tester->getFacade()->writeCollectionByProductOfferStoreEvents($eventEntityTransfers);

        // Assert
        $this->tester->assertProductOfferStorageEntities($this->tester->getProductOfferStorageEntities(), $sellableProductOfferTransfer);
    }

    /**
     * @dataProvider \SprykerTest\Zed\ProductOfferStorage\ProductOfferStorageBusinessTester::getNotSellableProductOfferDataProvider
     *
     * @param array<string, mixed> $productOfferData
     * @param array<string, mixed> $productData
     *
     * @return void
     */
    public function testDeletesNotSellableAndCreatesSellableProductOffersStorageEntity(
        array $productOfferData,
        array $productData
    ): void {
        // Arrange
        $sellableProductOfferTransfer = $this->tester->haveProductOfferWithStore($this->tester->getSellableProductOfferData());
        $notSellableProductOfferTransfer = $this->tester->haveProductOfferWithStore($productOfferData, $productData);
        $eventEntityTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER => $sellableProductOfferTransfer->getIdProductOffer(),
                SpyProductOfferStoreTableMap::COL_FK_STORE => $sellableProductOfferTransfer->getStores()[0]->getIdStore(),
            ]),
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER => $notSellableProductOfferTransfer->getIdProductOffer(),
                SpyProductOfferStoreTableMap::COL_FK_STORE => $notSellableProductOfferTransfer->getStores()[0]->getIdStore(),
            ]),
        ];
        $this->tester->haveProductOfferStorage(
            (new ProductOfferStorageTransfer())->setProductOfferReference($notSellableProductOfferTransfer->getProductOfferReference()),
            (new StoreTransfer())->setName($notSellableProductOfferTransfer->getStores()[0]->getNameOrFail()),
        );

        // Act
        $this->tester->getFacade()->writeCollectionByProductOfferStoreEvents($eventEntityTransfers);

        // Assert
        $this->tester->assertProductOfferStorageEntities($this->tester->getProductOfferStorageEntities(), $sellableProductOfferTransfer);
    }
}
