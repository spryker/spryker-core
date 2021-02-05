<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\MerchantProductOffer\Dependency\MerchantProductOfferEvents;
use Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductOfferStorageDeleterInterface;
use Spryker\Zed\MerchantProductOfferStorage\Business\Writer\ProductOfferStorageWriter;
use Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\Event\Listener\ProductOfferStoragePublishListener;
use Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface;
use Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageRepositoryInterface;

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
class ProductOfferStoragePublishListenerTest extends AbstractStoragePublishListenerTest
{
    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_DENIED
     */
    protected const STATUS_DENIED = 'denied';

    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_APPROVED
     */
    protected const STATUS_APPROVED = 'approved';

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
        $productOfferTransfer = $this->tester->createProductOffer($this->tester->getLocator()->store()->facade()->getCurrentStore());

        $eventTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference()]),
        ];

        //Act
        $this->productOfferStoragePublishListener->handleBulk(
            $eventTransfers,
            MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_PUBLISH
        );
        $merchantProductOfferStorageEntities = $this->tester->getProductOfferEntities($productOfferTransfer->getProductOfferReference());

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
        $productOfferTransfer = $this->tester->createProductOffer(
            $this->tester->getLocator()->store()->facade()->getCurrentStore(),
            [ProductOfferTransfer::IS_ACTIVE => false]
        );
        $eventTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference()]),
        ];

        //Act
        $this->productOfferStoragePublishListener->handleBulk(
            $eventTransfers,
            MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_PUBLISH
        );
        $merchantProductOfferStorageEntities = $this->tester->getProductOfferEntities($productOfferTransfer->getProductOfferReference());

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
        $productOfferTransfer = $this->tester->createProductOffer(
            $this->tester->getLocator()->store()->facade()->getCurrentStore(),
            [ProductOfferTransfer::APPROVAL_STATUS => static::STATUS_DENIED]
        );
        $eventTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference()]),
        ];

        //Act
        $this->productOfferStoragePublishListener->handleBulk(
            $eventTransfers,
            MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_PUBLISH
        );
        $merchantProductOfferStorageEntities = $this->tester->getProductOfferEntities($productOfferTransfer->getProductOfferReference());

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
        $productOfferTransfer = $this->tester->createProductOffer(
            $this->tester->getLocator()->store()->facade()->getCurrentStore(),
            [],
            [ProductConcreteTransfer::IS_ACTIVE => false]
        );
        $eventTransfers = [
            (new EventEntityTransfer())->setAdditionalValues([SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference()]),
        ];

        //Act
        $this->productOfferStoragePublishListener->handleBulk(
            $eventTransfers,
            MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_PUBLISH
        );
        $merchantProductOfferStorageEntities = $this->tester->getProductOfferEntities($productOfferTransfer->getProductOfferReference());

        //Assert
        $this->assertCount($expectedCount, $merchantProductOfferStorageEntities);
    }

    /**
     * @return void
     */
    public function testProductOfferStoragePublishListenerWithStoreRelatedData(): void
    {
        //Arrange
        $productOfferCollectionTransfer = $this->tester->getProductOfferCollectionTransfer();

        /** @var \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer */
        $productOfferTransfer = $productOfferCollectionTransfer->getProductOffers()[0];

        /** @var \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface|\PHPUnit\Framework\MockObject\MockObject $merchantProductOfferStorageEntityManager */
        $merchantProductOfferStorageEntityManager = $this->getMockBuilder(MerchantProductOfferStorageEntityManagerInterface::class)->getMock();
        $merchantProductOfferStorageEntityManager->expects($this->once())
            ->method('saveProductOfferStorage')
            ->with($productOfferTransfer);

        $productOfferCriteriaTransfer = (new ProductOfferCriteriaTransfer())
            ->setProductOfferReferences([$productOfferTransfer->getProductOfferReference()])
            ->setIsActive(true)
            ->setIsActiveConcreteProduct(true)
            ->addApprovalStatus(static::STATUS_APPROVED);

        $productOfferCollectionTransfer = (new ProductOfferCollectionTransfer())->addProductOffer($productOfferTransfer);

        /** @var \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject $merchantProductOfferStorageRepository */
        $merchantProductOfferStorageRepository = $this->getMockBuilder(MerchantProductOfferStorageRepositoryInterface::class)->getMock();
        $merchantProductOfferStorageRepository->expects($this->once())
            ->method('getProductOffersByFilterCriteria')
            ->with($productOfferCriteriaTransfer)
            ->willReturn($productOfferCollectionTransfer);

        /** @var \Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductOfferStorageDeleterInterface|\PHPUnit\Framework\MockObject\MockObject $productOfferStorageDeleter */
        $productOfferStorageDeleter = $this->getMockBuilder(ProductOfferStorageDeleterInterface::class)->getMock();
        $productOfferStorageDeleter->expects($this->exactly(2))
            ->method('deleteCollectionByProductOfferReferences')
            ->withConsecutive(
                [[$productOfferTransfer->getProductOfferReference()], 'AT'],
                [[$productOfferTransfer->getProductOfferReference()], 'US']
            );

        $productOfferStorageWriter = new ProductOfferStorageWriter(
            $this->getMerchantProductOfferStorageToEventBehaviorFacadeInterfaceMock($productOfferTransfer),
            $merchantProductOfferStorageEntityManager,
            $merchantProductOfferStorageRepository,
            $productOfferStorageDeleter,
            $this->getMerchantProductOfferStorageToStoreFacadeInterfaceMock()
        );

        //Act
        $productOfferStorageWriter->writeCollectionByProductOfferReferenceEvents([new EventEntityTransfer()]);
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
        $merchantProductOfferStorageEntities = $this->tester->findAllProductOfferEntities();

        foreach ($merchantProductOfferStorageEntities as $merchantProductOfferStorageEntity) {
            $merchantProductOfferStorageEntity->delete();
        }
    }
}
