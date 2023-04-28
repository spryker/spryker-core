<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductOfferStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\PriceProductOffer\Dependency\PriceProductOfferEvents;
use Spryker\Zed\PriceProductOfferStorage\Communication\Plugin\Event\Listener\PriceProductOfferStoragePublishListener;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductOfferStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group PriceProductOfferStoragePublishListenerTest
 * Add your own group annotations below this line
 */
class PriceProductOfferStoragePublishListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProductOfferStorage\PriceProductOfferStorageTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\PriceProductOfferStorage\Communication\Plugin\Event\Listener\PriceProductOfferStoragePublishListener
     */
    protected $priceProductOfferStoragePublishListener;

    /**
     * @var \Generated\Shared\Transfer\PriceProductOfferTransfer
     */
    protected $priceProductOfferTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer;

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

        $this->priceProductOfferStoragePublishListener = new PriceProductOfferStoragePublishListener();

        $this->productConcreteTransfer = $this->tester->haveProduct();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::CONCRETE_SKU => $this->productConcreteTransfer->getSku(),
        ]);

        $this->priceProductOfferTransfer = $this->tester->havePriceProductOffer([
            PriceProductOfferTransfer::FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
            PriceProductTransfer::SKU_PRODUCT => $this->productConcreteTransfer->getSku(),
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $this->productConcreteTransfer->getAbstractSku(),
        ]);
    }

    /**
     * @return void
     */
    public function testProductOfferStoragePublishListener(): void
    {
        //Arrange
        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->priceProductOfferTransfer->getIdPriceProductOffer()),
        ];

        //Act
        $this->priceProductOfferStoragePublishListener->handleBulk(
            $eventTransfers,
            PriceProductOfferEvents::ENTITY_SPY_PRICE_PRODUCT_OFFER_PUBLISH,
        );

        $countPriceProductOfferStorageEntities = $this->tester->getCountPriceProductOfferStorageCount($this->productConcreteTransfer->getIdProductConcrete());

        //Assert
        $this->assertSame(1, $countPriceProductOfferStorageEntities);
    }

    /**
     * @return void
     */
    protected function _after(): void
    {
        parent::_after();

        $this->tester->deletePriceProductOfferStorageEntities();
    }
}
