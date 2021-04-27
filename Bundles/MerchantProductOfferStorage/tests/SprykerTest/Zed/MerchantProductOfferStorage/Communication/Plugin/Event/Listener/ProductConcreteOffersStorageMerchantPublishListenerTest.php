<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\MerchantProductOffer\Dependency\MerchantProductOfferEvents;
use Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\Event\Listener\ProductConcreteOffersStorageMerchantPublishListener;

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
 * @group ProductConcreteOffersStorageMerchantPublishListenerTest
 * Add your own group annotations below this line
 */
class ProductConcreteOffersStorageMerchantPublishListenerTest extends AbstractStoragePublishListenerTest
{
    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\Event\Listener\ProductConcreteOffersStorageMerchantPublishListener
     */
    protected $productConcreteOffersStorageMerchantPublishListener;

    /**
     * @var \SprykerTest\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageTester
     */
    protected $tester;

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

        $this->productConcreteOffersStorageMerchantPublishListener = new ProductConcreteOffersStorageMerchantPublishListener();
    }

    /**
     * @return void
     */
    public function testProductConcreteOffersStorageMerchantPublishListenerSaveCollectionByProductSkusSuccessfully(): void
    {
        $expectedCount = 1;
        $productOfferTransfer = $this->tester->createProductOffer($this->tester->getLocator()->store()->facade()->getCurrentStore());
        $eventTransfers = [
            (new EventEntityTransfer())->setId($productOfferTransfer->getFkMerchant()),
        ];

        $this->productConcreteOffersStorageMerchantPublishListener->handleBulk(
            $eventTransfers,
            MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_PUBLISH
        );
        $merchantProductOfferStorageEntities = $this->tester->getProductConcreteProductOffersEntities($productOfferTransfer->getConcreteSku());

        $this->assertCount($expectedCount, $merchantProductOfferStorageEntities);
    }

    /**
     * @return void
     */
    public function testProductConcreteOffersStorageMerchantPublishListenerWithoutProductSkusDoesNotSaveAnyStorageEntity(): void
    {
        $expectedCount = 0;
        $merchantTransfer = $this->tester->haveMerchant();
        $eventTransfers = [
            (new EventEntityTransfer())->setId($merchantTransfer->getIdMerchant()),
        ];
        $productOfferTransfer = $this->tester->createProductOffer($this->tester->getLocator()->store()->facade()->getCurrentStore());

        $this->productConcreteOffersStorageMerchantPublishListener->handleBulk(
            $eventTransfers,
            MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_PUBLISH
        );
        $merchantProductOfferStorageEntities = $this->tester->getProductConcreteProductOffersEntities($productOfferTransfer->getConcreteSku());

        $this->assertCount($expectedCount, $merchantProductOfferStorageEntities);
    }
}
