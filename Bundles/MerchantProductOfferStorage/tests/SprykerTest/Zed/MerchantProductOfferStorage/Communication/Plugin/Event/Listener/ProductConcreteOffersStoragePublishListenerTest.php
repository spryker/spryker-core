<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\MerchantProductOffer\Dependency\MerchantProductOfferEvents;
use Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\Event\Listener\ProductConcreteOffersStoragePublishListener;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOfferStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductConcreteOffersStoragePublishListenerTest
 * Add your own group annotations below this line
 */
class ProductConcreteOffersStoragePublishListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected $merchantProductOfferTransfer;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\Event\Listener\ProductConcreteOffersStoragePublishListener
     */
    protected $productConcreteOffersStoragePublishListener;

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

        $this->merchantProductOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $this->tester->haveMerchant()->getIdMerchant(),
        ]);

        $this->productConcreteOffersStoragePublishListener = new ProductConcreteOffersStoragePublishListener();
    }

    /**
     * @return void
     */
    public function testProductConcreteOffersStoragePublishListener(): void
    {
        //Arrange
        $expectedCount = 1;
        $eventTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([SpyProductOfferTableMap::COL_CONCRETE_SKU => $this->merchantProductOfferTransfer->getConcreteSku()]),
        ];

        //Act
        $this->productConcreteOffersStoragePublishListener->handleBulk(
            $eventTransfers,
            MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_KEY_PUBLISH
        );
        $merchantProductOfferStorageEntities = $this->tester->getProductConcreteProductOffersEntities($this->merchantProductOfferTransfer->getConcreteSku());

        //Assert
        $this->assertCount($expectedCount, $merchantProductOfferStorageEntities);
    }

    /**
     * @return void
     */
    protected function _after(): void
    {
        parent::_after();

        $this->cleanUpProductConcreteOffersStorage();
    }

    /**
     * @return void
     */
    protected function cleanUpProductConcreteOffersStorage(): void
    {
        $merchantProductOfferStorageEntities = $this->tester->getProductConcreteProductOffersEntities($this->merchantProductOfferTransfer->getConcreteSku());

        foreach ($merchantProductOfferStorageEntities as $merchantProductOfferStorageEntity) {
            $merchantProductOfferStorageEntity->delete();
        }
    }
}
