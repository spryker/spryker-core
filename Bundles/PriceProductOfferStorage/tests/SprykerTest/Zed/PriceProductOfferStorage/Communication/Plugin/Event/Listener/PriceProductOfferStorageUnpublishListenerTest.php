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
use Orm\Zed\PriceProductOffer\Persistence\Map\SpyPriceProductOfferTableMap;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\PriceProductOffer\Dependency\PriceProductOfferEvents;
use Spryker\Zed\PriceProductOfferStorage\Communication\Plugin\Event\Listener\PriceProductOfferStoragePublishListener;
use Spryker\Zed\PriceProductOfferStorage\Communication\Plugin\Event\Listener\PriceProductOfferStorageUnpublishListener;

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
 * @group PriceProductOfferStorageUnpublishListenerTest
 * Add your own group annotations below this line
 */
class PriceProductOfferStorageUnpublishListenerTest extends Unit
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
     * @var \Spryker\Zed\PriceProductOfferStorage\Communication\Plugin\Event\Listener\PriceProductOfferStorageUnpublishListener
     */
    protected $productOfferStorageUnpublishListener;

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
                $this->tester->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->priceProductOfferStoragePublishListener = new PriceProductOfferStoragePublishListener();
        $this->productOfferStorageUnpublishListener = new PriceProductOfferStorageUnpublishListener();

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
    public function testProductOfferStorageUnpublishListener(): void
    {
        //Arrange
        $eventPublishTransfers = [
            (new EventEntityTransfer())->setId($this->priceProductOfferTransfer->getIdPriceProductOffer()),
        ];
        $eventUnpublishTransfers = [
            (new EventEntityTransfer())
                ->setId($this->priceProductOfferTransfer->getIdPriceProductOffer())
                ->setForeignKeys([
                SpyPriceProductOfferTableMap::COL_FK_PRODUCT_OFFER => $this->priceProductOfferTransfer->getFkProductOffer(),
                ]),
        ];
        //Act
        $this->priceProductOfferStoragePublishListener->handleBulk(
            $eventPublishTransfers,
            PriceProductOfferEvents::ENTITY_SPY_PRICE_PRODUCT_OFFER_PUBLISH,
        );

        $this->productOfferStorageUnpublishListener->handleBulk(
            $eventUnpublishTransfers,
            PriceProductOfferEvents::ENTITY_SPY_PRICE_PRODUCT_OFFER_UNPUBLISH,
        );
        $countPriceProductOfferStorageEntities = $this->tester->getCountPriceProductOfferStorageCount($this->productConcreteTransfer->getIdProductConcrete());

        //Assert
        $this->assertSame(0, $countPriceProductOfferStorageEntities);
    }
}
