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
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_APPROVED
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

        $this->productConcreteOffersStoragePublishListener = new ProductConcreteOffersStoragePublishListener();
    }

    /**
     * @return void
     */
    public function testProductConcreteOffersStoragePublishListener(): void
    {
        //Arrange
        $expectedCount = 1;
        $this->createProductOffer();
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
    public function testProductConcreteOffersStoragePublishListenerNotStoreDataIfProductOfferIsNotActive(): void
    {
        //Arrange
        $expectedCount = 0;
        $this->createProductOffer(
            [ProductOfferTransfer::IS_ACTIVE => false]
        );
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
    public function testProductConcreteOffersStoragePublishListenerNotStoreDataIfProductOfferIsNotApproved(): void
    {
        //Arrange
        $expectedCount = 0;
        $this->createProductOffer(
            [ProductOfferTransfer::APPROVAL_STATUS => static::STATUS_DECLINED]
        );
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
    public function testProductConcreteOffersStoragePublishListenerNotStoreDataIfProductConcreteIsNotActive(): void
    {
        //Arrange
        $expectedCount = 0;
        $this->createProductOffer(
            [],
            [ProductConcreteTransfer::IS_ACTIVE => false]
        );
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
     * @param array $productOfferData
     * @param array $productData
     *
     * @return void
     */
    protected function createProductOffer(array $productOfferData = [], array $productData = []): void
    {
        $storeTransfer = $this->tester->getLocator()->store()->facade()->getCurrentStore();

        $productOfferData[ProductOfferTransfer::FK_MERCHANT] = $this->tester->haveMerchant()->getIdMerchant();
        $productOfferData[ProductOfferTransfer::CONCRETE_SKU] = $this->tester->haveProduct($productData)->getSku();

        $productOfferTransfer = $this->tester->haveProductOffer($productOfferData)->addStore($storeTransfer);

        $this->tester->haveProductOfferStore($productOfferTransfer, $storeTransfer);
        $this->merchantProductOfferTransfer = $productOfferTransfer;
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
