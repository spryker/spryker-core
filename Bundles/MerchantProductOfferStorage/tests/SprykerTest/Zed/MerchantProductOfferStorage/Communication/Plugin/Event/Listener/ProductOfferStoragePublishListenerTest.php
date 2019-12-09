<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\MerchantProductOffer\Dependency\MerchantProductOfferEvents;
use Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\Event\Listener\ProductOfferStoragePublishListener;

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
 * @group ProductOfferStoragePublishListenerTest
 * Add your own group annotations below this line
 */
class ProductOfferStoragePublishListenerTest extends Unit
{
    /**
     * @uses \Spryker\Zed\ProductOffer\ProductOfferConfig::STATUS_APPROVED
     */
    protected const STATUS_DECLINED = 'declined';

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

        $this->productOfferStoragePublishListener = new ProductOfferStoragePublishListener();
    }

    /**
     * @return void
     */
    public function testProductOfferStoragePublishListener(): void
    {
        //Arrange
        $expectedCount = 1;
        $this->createProductOffer();
        $eventTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $this->merchantProductOfferTransfer->getProductOfferReference()]),
        ];

        //Act
        $this->productOfferStoragePublishListener->handleBulk(
            $eventTransfers,
            MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_KEY_PUBLISH
        );
        $merchantProductOfferStorageEntities = $this->tester->getProductOfferEntities($this->merchantProductOfferTransfer->getProductOfferReference());

        //Assert
        $this->assertCount($expectedCount, $merchantProductOfferStorageEntities);
    }

    /**
     * @return void
     */
    public function testProductOfferStoragePublishListenerNotStoreDataIfProductOfferIsNotActive(): void
    {
        //Arrange
        $expectedCount = 0;
        $this->createProductOffer(
            [ProductOfferTransfer::IS_ACTIVE => false]
        );
        $eventTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $this->merchantProductOfferTransfer->getProductOfferReference()]),
        ];

        //Act
        $this->productOfferStoragePublishListener->handleBulk(
            $eventTransfers,
            MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_KEY_PUBLISH
        );
        $merchantProductOfferStorageEntities = $this->tester->getProductOfferEntities($this->merchantProductOfferTransfer->getProductOfferReference());

        //Assert
        $this->assertCount($expectedCount, $merchantProductOfferStorageEntities);
    }

    /**
     * @return void
     */
    public function testProductOfferStoragePublishListenerNotStoreDataIfProductOfferIsNotApproved(): void
    {
        //Arrange
        $expectedCount = 0;
        $this->createProductOffer(
            [ProductOfferTransfer::APPROVAL_STATUS => static::STATUS_DECLINED]
        );
        $eventTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $this->merchantProductOfferTransfer->getProductOfferReference()]),
        ];

        //Act
        $this->productOfferStoragePublishListener->handleBulk(
            $eventTransfers,
            MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_KEY_PUBLISH
        );
        $merchantProductOfferStorageEntities = $this->tester->getProductOfferEntities($this->merchantProductOfferTransfer->getProductOfferReference());

        //Assert
        $this->assertCount($expectedCount, $merchantProductOfferStorageEntities);
    }

    /**
     * @return void
     */
    public function testProductOfferStoragePublishListenerNotStoreDataIfProductConcreteIsNotActive(): void
    {
        //Arrange
        $expectedCount = 0;
        $this->createProductOffer(
            [],
            [ProductConcreteTransfer::IS_ACTIVE => false]
        );
        $eventTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $this->merchantProductOfferTransfer->getProductOfferReference()]),
        ];

        //Act
        $this->productOfferStoragePublishListener->handleBulk(
            $eventTransfers,
            MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_KEY_PUBLISH
        );
        $merchantProductOfferStorageEntities = $this->tester->getProductOfferEntities($this->merchantProductOfferTransfer->getProductOfferReference());

        //Assert
        $this->assertCount($expectedCount, $merchantProductOfferStorageEntities);
    }

    /**
     * @param array $productOfferData
     * @param array $productData
     *
     * @return void
     */
    protected function createProductOffer(array $productOfferData = [], array $productData = []): void
    {
        $productOfferData[ProductOfferTransfer::FK_MERCHANT] = $this->tester->haveMerchant()->getIdMerchant();
        $productOfferData[ProductOfferTransfer::CONCRETE_SKU] = $this->tester->haveProduct($productData)->getSku();

        $this->merchantProductOfferTransfer = $this->tester->haveProductOffer($productOfferData);
    }

    /**
     * @return void
     */
    protected function _after(): void
    {
        parent::_after();

        $this->cleanUpProductOfferStorage();
    }

    /**
     * @return void
     */
    protected function cleanUpProductOfferStorage(): void
    {
        $merchantProductOfferStorageEntities = $this->tester->getProductOfferEntities($this->merchantProductOfferTransfer->getProductOfferReference());

        foreach ($merchantProductOfferStorageEntities as $merchantProductOfferStorageEntity) {
            $merchantProductOfferStorageEntity->delete();
        }
    }
}
