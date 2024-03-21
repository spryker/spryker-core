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
 * @group DeleteCollectionByProductOfferStoreEventsTest
 * Add your own group annotations below this line
 */
class DeleteCollectionByProductOfferStoreEventsTest extends Unit
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
    public function testDeletesProductOfferStorageEntityWhenProductOfferStoreExists(): void
    {
        // Arrange
        $storeTransferDe = $this->tester->haveStoreByName(static::STORE_NAME_DE);
        $storeTransferAt = $this->tester->haveStoreByName(static::STORE_NAME_AT);
        $productOfferTransfer = $this->tester->haveProductOffer();
        $this->tester->haveProductOfferStore($productOfferTransfer, $storeTransferDe);
        $this->tester->haveProductOfferStore($productOfferTransfer, $storeTransferAt);
        $productOfferTransfer->addStore($storeTransferDe);

        $this->tester->haveProductOfferStorage(
            (new ProductOfferStorageTransfer())
                ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
                ->setProductConcreteSku($productOfferTransfer->getConcreteSku()),
            (new StoreTransfer())->setName(static::STORE_NAME_DE),
        );
        $this->tester->haveProductOfferStorage(
            (new ProductOfferStorageTransfer())
                ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
                ->setProductConcreteSku($productOfferTransfer->getConcreteSku()),
            (new StoreTransfer())->setName(static::STORE_NAME_AT),
        );

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
                SpyProductOfferStoreTableMap::COL_FK_STORE => $storeTransferAt->getIdStore(),
            ]),
        ];

        // Act
        $this->tester->getFacade()->deleteCollectionByProductOfferStoreEvents($eventTransfers);

        // Assert
        $this->tester->assertProductOfferStorageEntities($this->tester->getProductOfferStorageEntities(), $productOfferTransfer);
    }

    /**
     * @return void
     */
    public function testDeletesProductOfferStorageEntityWhenProductOfferStoreDoesNotExist(): void
    {
        // Arrange
        $storeTransferDe = $this->tester->haveStoreByName(static::STORE_NAME_DE);
        $storeTransferAt = $this->tester->haveStoreByName(static::STORE_NAME_AT);
        $productOfferTransfer = $this->tester->haveProductOffer();
        $this->tester->haveProductOfferStore($productOfferTransfer, $storeTransferDe);
        $productOfferTransfer->addStore($storeTransferDe);

        $this->tester->haveProductOfferStorage(
            (new ProductOfferStorageTransfer())
                ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
                ->setProductConcreteSku($productOfferTransfer->getConcreteSku()),
            (new StoreTransfer())->setName(static::STORE_NAME_DE),
        );
        $this->tester->haveProductOfferStorage(
            (new ProductOfferStorageTransfer())
                ->setProductOfferReference($productOfferTransfer->getProductOfferReference())
                ->setProductConcreteSku($productOfferTransfer->getConcreteSku()),
            (new StoreTransfer())->setName(static::STORE_NAME_AT),
        );

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
                SpyProductOfferStoreTableMap::COL_FK_STORE => $storeTransferAt->getIdStore(),
            ]),
        ];

        // Act
        $this->tester->getFacade()->deleteCollectionByProductOfferStoreEvents($eventTransfers);

        // Assert
        $this->tester->assertProductOfferStorageEntities($this->tester->getProductOfferStorageEntities(), $productOfferTransfer);
    }
}
