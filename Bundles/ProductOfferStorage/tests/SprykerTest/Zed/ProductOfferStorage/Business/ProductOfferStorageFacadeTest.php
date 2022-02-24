<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
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
     * @var \SprykerTest\Zed\ProductOfferStorage\ProductOfferStorageFacadeTester
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
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });
    }

    /**
     * @return void
     */
    public function testWriteProductConcreteProductOffersStorageCollectionByProductEvents(): void
    {
        // Assign
        $this->tester->clearProductOfferData();
        $storeTransfer = $this->tester->getLocator()->store()->facade()->getCurrentStore();
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
    public function testWriteProductOfferStorageCollectionByProductOfferEvents(): void
    {
        // Assign
        $this->tester->clearProductOfferData();
        $storeTransfer = $this->tester->getLocator()->store()->facade()->getCurrentStore();
        $productTransfer = $this->tester->haveProduct();

        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::CONCRETE_SKU => $productTransfer->getSku(),
        ])->addStore($storeTransfer);

        $this->tester->haveProductOfferStore($productOfferTransfer, $storeTransfer);

        $eventTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([
                SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference(),
            ]),
        ];

        // Act
        $this->tester->getFacade()->writeProductOfferStorageCollectionByProductOfferEvents($eventTransfers);

        $this->assertCount(
            1,
            $this->tester->getProductOfferStorageEntities([$productOfferTransfer->getProductOfferReference()]),
        );
    }

    /**
     * @return void
     */
    public function testDeleteProductOfferStorageCollectionByProductOfferEvents(): void
    {
        // Assign
        $storeTransfer = $this->tester->getLocator()->store()->facade()->getCurrentStore();
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
}
