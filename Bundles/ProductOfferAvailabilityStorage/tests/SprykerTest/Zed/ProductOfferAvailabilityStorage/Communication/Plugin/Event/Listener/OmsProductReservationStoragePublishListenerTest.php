<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferAvailabilityStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\OmsProductReservationTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\Oms\Dependency\OmsEvents;
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
        $stockQuantity = 5;
        $reservedQuantity = 1;
        $expectedAvailability = $stockQuantity - $reservedQuantity;

        $storeTransfer = $this->tester->haveStore();
        $productOfferStockTransfer = $this->tester->haveProductOfferStock([
            ProductOfferStockTransfer::QUANTITY => $stockQuantity,
            ProductOfferStockTransfer::STOCK => [
                StockTransfer::STORE_RELATION => [
                    StoreRelationTransfer::ID_STORES => [
                        $storeTransfer->getIdStore(),
                    ],
                ],
            ],
        ]);

        $omsProductReservationTransfer = $this->tester->haveOmsProductReservation([
            OmsProductReservationTransfer::FK_STORE => $storeTransfer->getIdStore(),
            OmsProductReservationTransfer::RESERVATION_QUANTITY => $reservedQuantity,
            OmsProductReservationTransfer::SKU => $productOfferStockTransfer->getProductOffer()->getConcreteSku(),
        ]);

        $omsProductReservationStoragePublishListener = new OmsProductReservationStoragePublishListener();
        $omsProductReservationStoragePublishListener->setFacade($this->tester->getFacade());

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($omsProductReservationTransfer->getIdOmsProductReservation()),
        ];

        // Act
        $omsProductReservationStoragePublishListener->handleBulk($eventEntityTransfers, OmsEvents::ENTITY_SPY_OMS_PRODUCT_RESERVATION_CREATE);

        // Assert
        $productOfferAvailability = $this->tester->getProductOfferAvailability(
            $storeTransfer->getName(),
            $productOfferStockTransfer->getProductOffer()->getProductOfferReference()
        );

        $this->assertSame($expectedAvailability, $productOfferAvailability->toInt());
    }
}
