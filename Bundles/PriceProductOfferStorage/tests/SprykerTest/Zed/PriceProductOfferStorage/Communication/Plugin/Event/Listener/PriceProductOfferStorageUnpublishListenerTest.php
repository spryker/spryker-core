<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductOfferStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
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
    protected const CURRENCY = 'EUR';
    protected const PRODUCT_SKU = 'PRODUCT_SKU';
    protected const STORE = 'DE';
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
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->priceProductOfferStoragePublishListener = new PriceProductOfferStoragePublishListener();
        $this->productOfferStorageUnpublishListener = new PriceProductOfferStorageUnpublishListener();

        $this->productConcreteTransfer = $this->tester->haveProduct();
        $productOfferTransfer = $this->tester->haveProductOffer([
            'fkMerchant' => $this->tester->haveMerchant()->getIdMerchant(),
            'concreteSku' => $this->productConcreteTransfer->getSku(),
        ]);
        $storeTransfer = $this->tester->haveStore(['name' => static::STORE]);
        $priceTypeTransfer = $this->tester->havePriceType();
        $idCurrency = $this->tester->haveCurrency(['name' => static::CURRENCY]);

        $this->priceProductOfferTransfer = $this->tester->havePriceProductOffer([
            'fkProductOffer' => $productOfferTransfer->getIdProductOffer(),
            'fkPriceType' => $priceTypeTransfer->getIdPriceType(),
            'fkStore' => $storeTransfer->getIdStore(),
            'fkCurrency' => $idCurrency,
        ]);
    }

    /**
     * @return void
     */
    public function testProductOfferStorageUnpublishListener(): void
    {
        //Arrange
        $expectedCount = 0;
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
            PriceProductOfferEvents::ENTITY_SPY_PRICE_PRODUCT_OFFER_PUBLISH
        );

        $this->productOfferStorageUnpublishListener->handleBulk(
            $eventUnpublishTransfers,
            PriceProductOfferEvents::ENTITY_SPY_PRICE_PRODUCT_OFFER_UNPUBLISH
        );
        $countPriceProductOfferStorageEntities = $this->tester->getCountPriceProductOfferStorageEntities($this->productConcreteTransfer->getIdProductConcrete());

        //Assert
        $this->assertSame($expectedCount, $countPriceProductOfferStorageEntities);
    }
}
