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
use Spryker\Zed\Oms\Dependency\OmsEvents;
use Spryker\Zed\ProductOffer\Dependency\ProductOfferEvents;
use Spryker\Zed\ProductOfferAvailability\Dependency\ProductOfferAvailabilityEvents;
use Spryker\Zed\ProductOfferAvailabilityStorage\Communication\Plugin\Event\Listener\OmsProductReservationStoragePublishListener;

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
 * @group OmsProductReservationStoragePublishListenerTest
 * Add your own group annotations below this line
 */
class OmsProductReservationStoragePublishListenerTest extends Unit
{
    protected const TEST_STORE_NAME = 'test-AT';
    protected const TEST_PRODUCT_OFFER_REFERENCE = 'test-product-offer-reference-1';

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
    public function testOmsProductReservationStoragePublishListenerStoresDataForProductOfferAvailability(): void
    {
        // Arrange
        $this->tester->truncateProductOffers();
        $this->tester->truncateProductOfferAvailabilityStorage();
        $this->tester->truncateOmsProductReservations();

        $this->tester->createProductOfferStock(5, static::TEST_STORE_NAME, static::TEST_PRODUCT_OFFER_REFERENCE);

        $omsProductReservationEntity = $this->tester->createOmsProductReservation(
            1,
            static::TEST_STORE_NAME
        );

        $omsProductReservationStoragePublishListener = new OmsProductReservationStoragePublishListener();
        $omsProductReservationStoragePublishListener->setFacade($this->tester->getFacade());

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($omsProductReservationEntity->getIdOmsProductReservation()),
        ];

        // Act
        $omsProductReservationStoragePublishListener->handleBulk($eventEntityTransfers, OmsEvents::ENTITY_SPY_OMS_PRODUCT_RESERVATION_CREATE);

        // Assert
        $productOfferAvailabilityStorageEntity = $this->tester->findProductOfferAvailabilityStorage(static::TEST_STORE_NAME, static::TEST_PRODUCT_OFFER_REFERENCE);

        $this->assertNotNull($productOfferAvailabilityStorageEntity);

        $availability = (int)$productOfferAvailabilityStorageEntity->getData()['availability'] ?? 0;

        $this->assertSame(4, $availability);
    }
}
