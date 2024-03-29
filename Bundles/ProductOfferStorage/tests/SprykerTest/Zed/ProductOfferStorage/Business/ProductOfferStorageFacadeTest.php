<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferStoreTableMap;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferStorage
 * @group Business
 * @group Facade
 * @group ProductOfferStorageFacadeTest
 * Add your own group annotations below this line
 */
class ProductOfferStorageFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const STORE_NAME_AT = 'AT';

    /**
     * @var \SprykerTest\Zed\ProductOfferStorage\ProductOfferStorageBusinessTester
     */
    protected $tester;

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

        $this->tester->clearProductOfferDataFromStorage();
    }

    /**
     * @return void
     */
    public function testWriteProductConcreteProductOffersStorageCollectionByProductEvents(): void
    {
        // Assign
        $this->tester->clearProductOfferData();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $productTransfer = $this->tester->haveProduct();

        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::CONCRETE_SKU => $productTransfer->getSku(),
        ])->addStore($storeTransfer);

        $this->tester->haveProductOfferStore($productOfferTransfer, $storeTransfer);

        $eventTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([
                SpyProductOfferTableMap::COL_CONCRETE_SKU => $productOfferTransfer->getConcreteSku(),
            ]),
        ];

        // Act
        $this->tester->getFacade()->writeProductConcreteProductOffersStorageCollectionByProductEvents($eventTransfers);

        $this->assertCount(
            1,
            $this->tester->getProductConcreteProductOffersEntities([$productOfferTransfer->getConcreteSku()]),
        );
    }

    /**
     * @return void
     */
    public function testDeleteProductConcreteProductOffersStorageCollectionByProductEvents(): void
    {
        // Assign
        $productTransfer = $this->tester->haveProduct();

        $eventTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([
                SpyProductOfferTableMap::COL_CONCRETE_SKU => $productTransfer->getSku(),
            ]),
        ];

        // Act
        $this->tester->getFacade()->deleteProductConcreteProductOffersStorageCollectionByProductEvents($eventTransfers);

        $this->assertCount(
            0,
            $this->tester->getProductConcreteProductOffersEntities([$productTransfer->getSku()]),
        );
    }

    /**
     * @return void
     */
    public function testDeleteProductOfferStorageCollectionByProductOfferEvents(): void
    {
        // Assign
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $productTransfer = $this->tester->haveProduct();

        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::CONCRETE_SKU => $productTransfer->getSku(),
        ])->addStore($storeTransfer);

        $eventTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([
                SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference(),
            ]),
        ];

        // Act
        $this->tester->getFacade()->deleteProductOfferStorageCollectionByProductOfferEvents($eventTransfers);

        $this->assertCount(
            0,
            $this->tester->getProductOfferStorageEntities([$productOfferTransfer->getProductOfferReference()]),
        );
    }

    /**
     * @return void
     */
    public function testWriteProductConcreteProductOffersStorageCollectionByProductOfferStoreEventsPublishesDeStoreSuccessfuly(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStoreByName(static::STORE_NAME_DE);
        $productOfferTransfer = $this->tester->createProductOffer(
            $storeTransfer,
        );
        $eventEntityTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
                SpyProductOfferStoreTableMap::COL_FK_STORE => $storeTransfer->getIdStore(),
            ]),
        ];

        // Act
        $this->tester->getFacade()->writeProductConcreteProductOffersStorageCollectionByProductOfferStoreEvents($eventEntityTransfers);
        $productConcreteProductOffersStorageData = $this->tester->getProductConcreteProductOffersEntities(
            [$productOfferTransfer->getConcreteSku()],
        );

        // Assert
        $this->assertCount(1, $productConcreteProductOffersStorageData);
        $this->assertSame($productConcreteProductOffersStorageData[0]->getData()[0], mb_strtolower($productOfferTransfer->getProductOfferReference()));
    }

    /**
     * @return void
     */
    public function testWriteProductConcreteProductOffersStorageCollectionByProductOfferStoreEventsPublishesAtStoreAndRemovesDeSuccessfuly(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStoreByName(static::STORE_NAME_AT);
        $productOfferTransfer = $this->tester->createProductOffer(
            $storeTransfer,
        );
        $this->tester->haveProductConcreteProductOfferStorage(
            $productOfferTransfer->getConcreteSku(),
            static::STORE_NAME_DE,
            ['test'],
        );

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
                SpyProductOfferStoreTableMap::COL_FK_STORE => $storeTransfer->getIdStore(),
            ]),
        ];

        // Act
        $this->tester->getFacade()->writeProductConcreteProductOffersStorageCollectionByProductOfferStoreEvents($eventTransfers);
        $productConcreteProductOffersStorageData = $this->tester->getProductConcreteProductOffersEntities(
            [$productOfferTransfer->getConcreteSku()],
        );

        // Assert
        $this->assertCount(1, $productConcreteProductOffersStorageData);
        $this->assertSame($productConcreteProductOffersStorageData[0]->getData()[0], mb_strtolower($productOfferTransfer->getProductOfferReference()));
        $this->assertSame($productConcreteProductOffersStorageData[0]->getStore(), $storeTransfer->getName());
    }

    /**
     * @return void
     */
    public function testWriteProductConcreteProductOffersStorageCollectionByProductOfferStoreEventsPublishesAtStoreByTwoOffersForOneConcreteSku()
    {
        // Arrange
        $storeTransfer = $this->tester->haveStoreByName(static::STORE_NAME_DE);
        $productOfferTransfer = $this->tester->createProductOffer($storeTransfer);
        $productOfferTransfer2 = $this->tester->createProductOfferWithConcreteSku(
            $storeTransfer,
            $productOfferTransfer->getConcreteSku(),
        );

        $this->tester->haveProductConcreteProductOfferStorage(
            $productOfferTransfer->getConcreteSku(),
            $storeTransfer->getName(),
            ['test'],
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
                SpyProductOfferStoreTableMap::COL_FK_STORE => $storeTransfer->getIdStore(),
            ]),
        ];

        // Act
        $this->tester->getFacade()->writeProductConcreteProductOffersStorageCollectionByProductOfferStoreEvents($eventEntityTransfers);

        $productConcreteProductOffersStorageEntities = $this->tester->getProductConcreteProductOffersEntities(
            [$productOfferTransfer->getConcreteSku()],
        );

        // Assert
        $this->assertSame($productConcreteProductOffersStorageEntities[0]->getStore(), $storeTransfer->getName());
        $productOfferReferences = $productConcreteProductOffersStorageEntities->getData()[0]->getData();
        $this->assertCount(2, $productOfferReferences);
        $this->assertContains(mb_strtolower($productOfferTransfer->getProductOfferReference()), $productOfferReferences);
        $this->assertContains(mb_strtolower($productOfferTransfer2->getProductOfferReference()), $productOfferReferences);
    }
}
