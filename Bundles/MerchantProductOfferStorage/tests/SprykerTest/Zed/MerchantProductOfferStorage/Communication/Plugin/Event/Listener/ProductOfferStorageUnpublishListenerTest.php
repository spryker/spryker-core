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
use Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\Event\Listener\ProductOfferStoragePublishListener;
use Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\Event\Listener\ProductOfferStorageUnpublishListener;

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
 * @group ProductOfferStorageUnpublishListenerTest
 * Add your own group annotations below this line
 */
class ProductOfferStorageUnpublishListenerTest extends Unit
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
     * @var \Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\Event\Listener\ProductOfferStoragePublishListener
     */
    protected $productOfferStoragePublishListener;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\Event\Listener\ProductOfferStorageUnpublishListener
     */
    protected $productOfferStorageUnpublishListener;

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
        $this->productOfferStoragePublishListener = new ProductOfferStoragePublishListener();
        $this->productOfferStorageUnpublishListener = new ProductOfferStorageUnpublishListener();
    }

    /**
     * @return void
     */
    public function testProductOfferStorageUnpublishListener(): void
    {
        //Arrange
        $expectedCount = 0;
        $eventTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $this->merchantProductOfferTransfer->getProductOfferReference()]),
        ];

        //Act
        $this->productOfferStoragePublishListener->handleBulk(
            $eventTransfers,
            MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_PUBLISH
        );
        $this->productOfferStorageUnpublishListener->handleBulk(
            $eventTransfers,
            MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_UNPUBLISH
        );
        $merchantProductOfferStorageEntities = $this->tester->getProductOfferEntities($this->merchantProductOfferTransfer->getProductOfferReference());

        //Assert
        $this->assertCount($expectedCount, $merchantProductOfferStorageEntities);
    }
}
