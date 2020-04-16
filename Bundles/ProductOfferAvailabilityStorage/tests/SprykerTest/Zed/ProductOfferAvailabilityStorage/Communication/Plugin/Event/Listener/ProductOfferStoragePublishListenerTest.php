<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferAvailabilityStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ReservationResponseTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\Oms\OmsDependencyProvider;
use Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationReaderStrategyPluginInterface;
use Spryker\Zed\ProductOffer\Dependency\ProductOfferEvents;
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
        $stockQuantity = 5;
        $expectedAvailability = $stockQuantity;

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

        $productOfferStoragePublishListener = new ProductOfferStoragePublishListener();
        $productOfferStoragePublishListener->setFacade($this->tester->getFacade());

        $this->setProductOfferOmsReservationReaderStrategyPluginReturn();

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($productOfferStockTransfer->getProductOffer()->getIdProductOffer()),
        ];

        // Act
        $productOfferStoragePublishListener->handleBulk($eventEntityTransfers, ProductOfferEvents::ENTITY_SPY_PRODUCT_OFFER_PUBLISH);

        // Assert
        $productOfferAvailability = $this->tester->getProductOfferAvailability(
            $storeTransfer->getName(),
            $productOfferStockTransfer->getProductOffer()->getProductOfferReference()
        );

        $this->assertSame($expectedAvailability, $productOfferAvailability->toInt());
    }

    /**
     * @return void
     */
    protected function setProductOfferOmsReservationReaderStrategyPluginReturn(): void
    {
        $reservationResponseTransfer = (new ReservationResponseTransfer())
            ->setReservationQuantity(0);

        $productOfferOmsReservationReaderStrategyPlugin = $this->getMockBuilder(OmsReservationReaderStrategyPluginInterface::class)->getMock();
        $productOfferOmsReservationReaderStrategyPlugin->method('isApplicable')->willReturn(true);
        $productOfferOmsReservationReaderStrategyPlugin->method('getReservationQuantity')->willReturn($reservationResponseTransfer);
        $this->tester->setDependency(OmsDependencyProvider::PLUGINS_OMS_RESERVATION_READER_STRATEGY, [$productOfferOmsReservationReaderStrategyPlugin]);
    }
}
