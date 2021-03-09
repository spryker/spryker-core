<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\MerchantProductOffer\Dependency\MerchantProductOfferEvents;
use Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductConcreteOffersStorageDeleterInterface;
use Spryker\Zed\MerchantProductOfferStorage\Business\Writer\ProductConcreteOffersStorageWriter;
use Spryker\Zed\MerchantProductOfferStorage\Business\Writer\ProductOfferCriteriaFilterTransferFactory;
use Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\Event\Listener\ProductConcreteOffersStoragePublishListener;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface;

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
class ProductConcreteOffersStoragePublishListenerTest extends AbstractStoragePublishListenerTest
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
        $productOfferTransfer = $this->tester->createProductOffer($this->tester->getLocator()->store()->facade()->getCurrentStore());
        $eventTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([SpyProductOfferTableMap::COL_CONCRETE_SKU => $productOfferTransfer->getConcreteSku()]),
        ];

        //Act
        $this->productConcreteOffersStoragePublishListener->handleBulk(
            $eventTransfers,
            MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_PUBLISH
        );
        $merchantProductOfferStorageEntities = $this->tester->getProductConcreteProductOffersEntities($productOfferTransfer->getConcreteSku());

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
        $productOfferTransfer = $this->tester->createProductOffer(
            $this->tester->getLocator()->store()->facade()->getCurrentStore(),
            [ProductOfferTransfer::IS_ACTIVE => false]
        );
        $eventTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([SpyProductOfferTableMap::COL_CONCRETE_SKU => $productOfferTransfer->getConcreteSku()]),
        ];

        //Act
        $this->productConcreteOffersStoragePublishListener->handleBulk(
            $eventTransfers,
            MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_PUBLISH
        );
        $merchantProductOfferStorageEntities = $this->tester->getProductConcreteProductOffersEntities($productOfferTransfer->getConcreteSku());

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
        $productOfferTransfer = $this->tester->createProductOffer(
            $this->tester->getLocator()->store()->facade()->getCurrentStore(),
            [ProductOfferTransfer::APPROVAL_STATUS => static::STATUS_DECLINED]
        );
        $eventTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([SpyProductOfferTableMap::COL_CONCRETE_SKU => $productOfferTransfer->getConcreteSku()]),
        ];

        //Act
        $this->productConcreteOffersStoragePublishListener->handleBulk(
            $eventTransfers,
            MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_PUBLISH
        );
        $merchantProductOfferStorageEntities = $this->tester->getProductConcreteProductOffersEntities($productOfferTransfer->getConcreteSku());

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
        $productOfferTransfer = $this->tester->createProductOffer(
            $this->tester->getLocator()->store()->facade()->getCurrentStore(),
            [],
            [ProductConcreteTransfer::IS_ACTIVE => false]
        );
        $eventTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([SpyProductOfferTableMap::COL_CONCRETE_SKU => $productOfferTransfer->getConcreteSku()]),
        ];

        //Act
        $this->productConcreteOffersStoragePublishListener->handleBulk(
            $eventTransfers,
            MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_PUBLISH
        );
        $merchantProductOfferStorageEntities = $this->tester->getProductConcreteProductOffersEntities($productOfferTransfer->getConcreteSku());

        //Assert
        $this->assertCount($expectedCount, $merchantProductOfferStorageEntities);
    }

    /**
     * @return void
     */
    public function testProductConcreteOffersStoragePublishListenerWithStoreRelatedData(): void
    {
        //Arrange
        $productOfferCollectionTransfer = $this->tester->getProductOfferCollectionTransfer();

        /** @var \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer */
        $productOfferTransfer = $productOfferCollectionTransfer->getProductOffers()[0];

        /** @var \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface|\PHPUnit\Framework\MockObject\MockObject $merchantProductOfferStorageEntityManager */
        $merchantProductOfferStorageEntityManager = $this->getMockBuilder(MerchantProductOfferStorageEntityManagerInterface::class)->getMock();
        $merchantProductOfferStorageEntityManager->expects($this->once())
            ->method('saveProductConcreteProductOffersStorage')
            ->with($productOfferTransfer->getConcreteSku(), [mb_strtolower($productOfferTransfer->getProductOfferReference())], 'DE');

        /** @var \Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductConcreteOffersStorageDeleterInterface|\PHPUnit\Framework\MockObject\MockObject $productOfferStorageDeleter */
        $productOfferStorageDeleter = $this->getMockBuilder(ProductConcreteOffersStorageDeleterInterface::class)->getMock();
        $productOfferStorageDeleter->expects($this->exactly(2))
            ->method('deleteCollectionByProductSkus')
            ->withConsecutive(
                [[$productOfferTransfer->getConcreteSku()], 'AT'],
                [[$productOfferTransfer->getConcreteSku()], 'US']
            );

        $eventBehaviorFacade = $this->getMockBuilder(MerchantProductOfferStorageToEventBehaviorFacadeInterface::class)->getMock();
        $eventBehaviorFacade->method('getEventTransfersAdditionalValues')->willReturn([$productOfferTransfer->getConcreteSku()]);

        $productOfferStorageWriter = new ProductConcreteOffersStorageWriter(
            $eventBehaviorFacade,
            $merchantProductOfferStorageEntityManager,
            $this->getMerchantProductOfferStorageRepositoryMock($productOfferCollectionTransfer),
            $productOfferStorageDeleter,
            $this->getMerchantProductOfferStorageToStoreFacadeInterfaceMock(),
            new ProductOfferCriteriaFilterTransferFactory()
        );

        //Act
        $productOfferStorageWriter->writeCollectionByProductSkuEvents([new EventEntityTransfer()]);
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
        $merchantProductOfferStorageEntities = $this->tester->findAllProductOfferEntities();

        foreach ($merchantProductOfferStorageEntities as $merchantProductOfferStorageEntity) {
            $merchantProductOfferStorageEntity->delete();
        }
    }
}
