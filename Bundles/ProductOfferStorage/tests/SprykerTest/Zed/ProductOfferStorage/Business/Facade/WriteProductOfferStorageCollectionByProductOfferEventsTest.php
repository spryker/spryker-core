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
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
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
 * @group WriteProductOfferStorageCollectionByProductOfferEventsTest
 * Add your own group annotations below this line
 */
class WriteProductOfferStorageCollectionByProductOfferEventsTest extends Unit
{
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
    public function testCreatesNewProductOffersStorageEntityByProductOfferReference(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOfferWithStore($this->tester->getSellableProductOfferData());
        $eventEntityTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([
                SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReferenceOrFail(),
            ]),
        ];

        // Act
        $this->tester->getFacade()->writeProductOfferStorageCollectionByProductOfferEvents($eventEntityTransfers);

        // Assert
        $this->tester->assertProductOfferStorageEntities($this->tester->getProductOfferStorageEntities(), $productOfferTransfer);
    }

    /**
     * @return void
     */
    public function testCreatesNewProductOffersStorageEntityByProductOfferId(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOfferWithStore($this->tester->getSellableProductOfferData());
        $eventEntityTransfers = [(new EventEntityTransfer())->setId($productOfferTransfer->getIdProductOfferOrFail())];

        // Act
        $this->tester->getFacade()->writeProductOfferStorageCollectionByProductOfferEvents($eventEntityTransfers);

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
    public function testDoesNotCreateNewNotSellableProductOffersStorageEntityByProductOfferReference(
        array $productOfferData,
        array $productData
    ): void {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOfferWithStore($productOfferData, $productData);
        $eventEntityTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([
                SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReferenceOrFail(),
            ]),
        ];

        // Act
        $this->tester->getFacade()->writeProductOfferStorageCollectionByProductOfferEvents($eventEntityTransfers);

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
    public function testDoesNotCreateNewNotSellableProductOffersStorageEntityByProductOfferId(
        array $productOfferData,
        array $productData
    ): void {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOfferWithStore($productOfferData, $productData);
        $eventEntityTransfers = [(new EventEntityTransfer())->setId($productOfferTransfer->getIdProductOfferOrFail())];

        // Act
        $this->tester->getFacade()->writeProductOfferStorageCollectionByProductOfferEvents($eventEntityTransfers);

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
    public function testDeletesNotSellableProductOffersStorageEntityByProductOfferReference(
        array $productOfferData,
        array $productData
    ): void {
        // Arrange
        $sellableProductOfferTransfer = $this->tester->haveProductOfferWithStore($this->tester->getSellableProductOfferData());
        $notSellableProductOfferTransfer = $this->tester->haveProductOfferWithStore($productOfferData, $productData);
        $eventEntityTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([
                SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $sellableProductOfferTransfer->getProductOfferReferenceOrFail(),
            ]),
            (new EventEntityTransfer())->setAdditionalValues([
                SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $notSellableProductOfferTransfer->getProductOfferReferenceOrFail(),
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
        $this->tester->getFacade()->writeProductOfferStorageCollectionByProductOfferEvents($eventEntityTransfers);

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
    public function testDeletesNotSellableProductOffersStorageEntityByProductOfferId(
        array $productOfferData,
        array $productData
    ): void {
        // Arrange
        $sellableProductOfferTransfer = $this->tester->haveProductOfferWithStore($this->tester->getSellableProductOfferData());
        $notSellableProductOfferTransfer = $this->tester->haveProductOfferWithStore($productOfferData, $productData);
        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($sellableProductOfferTransfer->getIdProductOfferOrFail()),
            (new EventEntityTransfer())->setId($notSellableProductOfferTransfer->getIdProductOfferOrFail()),
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
        $this->tester->getFacade()->writeProductOfferStorageCollectionByProductOfferEvents($eventEntityTransfers);

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
    public function testDeletesNotSellableAndCreatesSellableProductOffersStorageEntityByProductOfferReference(
        array $productOfferData,
        array $productData
    ): void {
        // Arrange
        $sellableProductOfferTransfer = $this->tester->haveProductOfferWithStore($this->tester->getSellableProductOfferData());
        $notSellableProductOfferTransfer = $this->tester->haveProductOfferWithStore($productOfferData, $productData);
        $eventEntityTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([
                SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $sellableProductOfferTransfer->getProductOfferReferenceOrFail(),
            ]),
            (new EventEntityTransfer())->setAdditionalValues([
                SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $notSellableProductOfferTransfer->getProductOfferReferenceOrFail(),
            ]),
        ];
        $this->tester->haveProductOfferStorage(
            (new ProductOfferStorageTransfer())->setProductOfferReference($notSellableProductOfferTransfer->getProductOfferReference()),
            (new StoreTransfer())->setName($notSellableProductOfferTransfer->getStores()[0]->getNameOrFail()),
        );

        // Act
        $this->tester->getFacade()->writeProductOfferStorageCollectionByProductOfferEvents($eventEntityTransfers);

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
    public function testDeletesNotSellableAndCreatesSellableProductOffersStorageEntityByProductOfferId(
        array $productOfferData,
        array $productData
    ): void {
        // Arrange
        $sellableProductOfferTransfer = $this->tester->haveProductOfferWithStore($this->tester->getSellableProductOfferData());
        $notSellableProductOfferTransfer = $this->tester->haveProductOfferWithStore($productOfferData, $productData);
        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($sellableProductOfferTransfer->getIdProductOfferOrFail()),
            (new EventEntityTransfer())->setId($notSellableProductOfferTransfer->getIdProductOfferOrFail()),
        ];
        $this->tester->haveProductOfferStorage(
            (new ProductOfferStorageTransfer())->setProductOfferReference($notSellableProductOfferTransfer->getProductOfferReference()),
            (new StoreTransfer())->setName($notSellableProductOfferTransfer->getStores()[0]->getNameOrFail()),
        );

        // Act
        $this->tester->getFacade()->writeProductOfferStorageCollectionByProductOfferEvents($eventEntityTransfers);

        // Assert
        $this->tester->assertProductOfferStorageEntities($this->tester->getProductOfferStorageEntities(), $sellableProductOfferTransfer);
    }
}
