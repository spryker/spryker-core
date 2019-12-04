<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferAvailabilityStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\ProductOfferAvailability\Dependency\ProductOfferAvailabilityEvents;
use Spryker\Zed\ProductOfferAvailabilityStorage\Communication\Plugin\Event\Listener\ProductOfferStoragePublishListener;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferAvailabilityStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductOfferStoragePublishListenerTest
 * Add your own group annotations below this line
 */
class ProductOfferStoragePublishListenerTest extends Unit
{
    protected const TEST_STORE_NAME = 'test-EN';
    protected const TEST_PRODUCT_OFFER_REFERENCE = 'test-product-offer-reference-3';

    /**
     * @var \SprykerTest\Zed\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
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
    public function testProductOfferStoragePublishListenerStoresDataForProductOfferAvailability(): void
    {
        // Arrange
        $this->tester->truncateProductOffers();
        $this->tester->truncateProductOfferAvailabilityStorage();

        $productOfferStockEntity = $this->tester->createProductOfferStock(1, static::TEST_STORE_NAME, static::TEST_PRODUCT_OFFER_REFERENCE);

        $productOfferStoragePublishListener = new ProductOfferStoragePublishListener();
        $productOfferStoragePublishListener->setFacade($this->tester->getFacade());

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($productOfferStockEntity->getFkProductOffer()),
        ];

        // Act
        $productOfferStoragePublishListener->handleBulk($eventEntityTransfers, ProductOfferAvailabilityEvents::ENTITY_SPY_PRODUCT_OFFER_PUBLISH);

        // Assert
        $productOfferAvailabilityStorageEntity = $this->tester->findProductOfferAvailabilityStorage(static::TEST_STORE_NAME, static::TEST_PRODUCT_OFFER_REFERENCE);

        $this->assertNotNull($productOfferAvailabilityStorageEntity);
    }
}
