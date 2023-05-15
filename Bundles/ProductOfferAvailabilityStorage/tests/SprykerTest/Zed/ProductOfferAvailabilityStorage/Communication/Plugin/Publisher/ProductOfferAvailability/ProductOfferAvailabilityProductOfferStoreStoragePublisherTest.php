<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferAvailabilityStorage\Communication\Plugin\Publisher\ProductOfferAvailability;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Shared\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageConfig;
use Spryker\Zed\ProductOffer\Dependency\ProductOfferEvents;
use Spryker\Zed\ProductOfferAvailabilityStorage\Communication\Plugin\Publisher\ProductOfferAvailability\ProductOfferAvailabilityProductOfferStoreStoragePublisherPlugin;
use SprykerTest\Zed\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferAvailabilityStorage
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group ProductOfferAvailability
 * @group ProductOfferAvailabilityProductOfferStoreStoragePublisherTest
 * Add your own group annotations below this line
 */
class ProductOfferAvailabilityProductOfferStoreStoragePublisherTest extends Unit
{
    /**
     * @uses \Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferStoreTableMap::COL_FK_PRODUCT_OFFER
     *
     * @var string
     */
    protected const COL_FK_PRODUCT_OFFER = 'spy_product_offer_store.fk_product_offer';

    /**
     * @var \SprykerTest\Zed\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageCommunicationTester
     */
    protected ProductOfferAvailabilityStorageCommunicationTester $tester;

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
    public function testProductOfferAvailabilityProductOfferStoreStoragePublisherStoresDataForProductOfferAvailability(): void
    {
        // Arrange
        $stockQuantity = 5;
        $expectedAvailability = $stockQuantity;

        $storeTransfer = $this->tester->haveStore();
        $productOfferTransfer = $this->tester->haveProductOffer();
        $this->tester->haveProductOfferStore($productOfferTransfer, $storeTransfer);
        $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
            ProductOfferStockTransfer::QUANTITY => $stockQuantity,
            ProductOfferStockTransfer::STOCK => [
                StockTransfer::STORE_RELATION => [
                    StoreRelationTransfer::ID_STORES => [
                        $storeTransfer->getIdStore(),
                    ],
                ],
            ],
        ]);

        $productOfferAvailabilityProductOfferStorageStoragePublisherPlugin = new ProductOfferAvailabilityProductOfferStoreStoragePublisherPlugin();
        $productOfferAvailabilityProductOfferStorageStoragePublisherPlugin->setFacade($this->tester->getFacade());

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                static::COL_FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
            ]),
        ];

        // Act
        $productOfferAvailabilityProductOfferStorageStoragePublisherPlugin->handleBulk(
            $eventEntityTransfers,
            ProductOfferAvailabilityStorageConfig::ENTITY_SPY_PRODUCT_OFFER_STORE_CREATE,
        );

        // Assert
        $productOfferAvailability = $this->tester->getProductOfferAvailability(
            $storeTransfer->getName(),
            $productOfferTransfer->getProductOfferReference(),
        );

        $this->assertSame($expectedAvailability, $productOfferAvailability->toInt());
    }
}
