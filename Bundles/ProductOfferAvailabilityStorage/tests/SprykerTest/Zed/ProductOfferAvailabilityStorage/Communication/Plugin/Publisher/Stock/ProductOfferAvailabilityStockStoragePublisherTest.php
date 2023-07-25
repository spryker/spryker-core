<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferAvailabilityStorage\Communication\Plugin\Publisher\Stock;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Shared\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageConfig;
use Spryker\Zed\ProductOfferAvailabilityStorage\Communication\Plugin\Publisher\Stock\ProductOfferAvailabilityStockStoragePublisherPlugin;
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
 * @group Stock
 * @group ProductOfferAvailabilityStockStoragePublisherTest
 * Add your own group annotations below this line
 */
class ProductOfferAvailabilityStockStoragePublisherTest extends Unit
{
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
    public function testStoresDataForProductOfferAvailability(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $productOfferTransfer = $this->tester->haveProductOffer();
        $this->tester->haveProductOfferStore($productOfferTransfer, $storeTransfer);
        $productOfferStockTransfer = $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
            ProductOfferStockTransfer::QUANTITY => 5,
            ProductOfferStockTransfer::STOCK => [
                StockTransfer::STORE_RELATION => [
                    StoreRelationTransfer::ID_STORES => [
                        $storeTransfer->getIdStore(),
                    ],
                ],
            ],
        ]);
        $this->tester->updateStock($productOfferStockTransfer->getStock()->setIsActive(true));

        $productOfferAvailabilityStockStoragePublisherPlugin = new ProductOfferAvailabilityStockStoragePublisherPlugin();
        $productOfferAvailabilityStockStoragePublisherPlugin->setFacade($this->tester->getFacade());

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($productOfferStockTransfer->getStock()->getIdStock()),
        ];

        // Act
        $productOfferAvailabilityStockStoragePublisherPlugin->handleBulk(
            $eventEntityTransfers,
            ProductOfferAvailabilityStorageConfig::ENTITY_SPY_STOCK_UPDATE,
        );

        // Assert
        $productOfferAvailability = $this->tester->getProductOfferAvailability(
            $storeTransfer->getName(),
            $productOfferTransfer->getProductOfferReference(),
        );

        $this->assertSame($productOfferStockTransfer->getQuantityOrFail()->toInt(), $productOfferAvailability->toInt());
    }
}
