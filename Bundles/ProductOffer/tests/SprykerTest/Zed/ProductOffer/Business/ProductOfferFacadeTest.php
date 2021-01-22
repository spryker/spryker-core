<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOffer\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductOfferBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductOffer\Business\ProductOfferBusinessFactory;
use Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface;
use Spryker\Zed\ProductOffer\ProductOfferDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOffer
 * @group Business
 * @group Facade
 * @group ProductOfferFacadeTest
 *
 * Add your own group annotations below this line
 */
class ProductOfferFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductOffer\ProductOfferBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->truncateProductOffers();
    }

    /**
     * @return void
     */
    public function testFind(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $this->tester->haveMerchant()->getIdMerchant(),
        ]);
        $productOfferCriteriaTransfer = new ProductOfferCriteriaTransfer();
        $productOfferCriteriaTransfer->setProductOfferReference($productOfferTransfer->getProductOfferReference());

        // Act
        $productOfferCollectionTransfer = $this->tester->getFacade()->get($productOfferCriteriaTransfer);
        // Assert
        $this->assertNotEmpty($productOfferCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testFindAddsStoresToProductOffer(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $storeTransfer1 = $this->tester->haveStore();
        $storeTransfer2 = $this->tester->haveStore();
        $this->tester->haveProductOfferStore($productOfferTransfer, $storeTransfer1);
        $this->tester->haveProductOfferStore($productOfferTransfer, $storeTransfer2);
        $productOfferCriteriaFilterTransfer = (new ProductOfferCriteriaFilterTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference());

        // Act
        $productOfferTransfer = $this->tester->getFacade()->find($productOfferCriteriaFilterTransfer)->getProductOffers()[0];

        // Assert
        $this->assertCount(2, $productOfferTransfer->getStores());
        $this->assertEquals($productOfferTransfer->getStores(), new ArrayObject([$storeTransfer1, $storeTransfer2]));
    }

    /**
     * @return void
     */
    public function testFindOneFindsProductOfferByProductOfferReference(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $productOfferCriteriaFilterTransfer = (new ProductOfferCriteriaFilterTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference());

        // Act
        $productOfferTransfer = $this->tester->getFacade()->findOne($productOfferCriteriaFilterTransfer);

        // Assert
        $this->assertNotEmpty($productOfferTransfer);
    }

    /**
     * @return void
     */
    public function testFindOneFindsProductOfferByProductOfferId(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $productOfferCriteriaFilterTransfer = (new ProductOfferCriteriaFilterTransfer())
            ->setIdProductOffer($productOfferTransfer->getIdProductOffer());

        // Act
        $productOfferTransfer = $this->tester->getFacade()->findOne($productOfferCriteriaFilterTransfer);

        // Assert
        $this->assertNotEmpty($productOfferTransfer);
    }

    /**
     * @return void
     */
    public function testFindOneAddsStoresToProductOffer(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $storeTransfer1 = $this->tester->haveStore();
        $storeTransfer2 = $this->tester->haveStore();
        $this->tester->haveProductOfferStore($productOfferTransfer, $storeTransfer1);
        $this->tester->haveProductOfferStore($productOfferTransfer, $storeTransfer2);
        $productOfferCriteriaFilterTransfer = (new ProductOfferCriteriaFilterTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference());

        // Act
        $productOfferTransfer = $this->tester->getFacade()->findOne($productOfferCriteriaFilterTransfer);

        // Assert
        $this->assertCount(2, $productOfferTransfer->getStores());
        $this->assertEquals($productOfferTransfer->getStores(), new ArrayObject([$storeTransfer1, $storeTransfer2]));
    }

    /**
     * @return void
     */
    public function testCreate(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $productOfferTransfer = (new ProductOfferBuilder([
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
        ]))->build();
        $productOfferTransfer->setIdProductOffer(null);

        // Act
        $this->tester->getFacade()->create($productOfferTransfer);

        // Assert
        $this->assertNotEmpty($productOfferTransfer->getIdProductOffer());
    }

    /**
     * @return void
     */
    public function testCreateCreatesRelationsBetweenProductOffersAndStores(): void
    {
        // Arrange
        $productOfferTransfer = (new ProductOfferBuilder([
            ProductOfferTransfer::FK_MERCHANT => $this->tester->haveMerchant()->getIdMerchant(),
            ProductOfferTransfer::ID_PRODUCT_OFFER => null,
        ]))->build();
        $productOfferTransfer->addStore($this->tester->haveStore());
        $productOfferTransfer->addStore($this->tester->haveStore());
        $productOfferCriteriaFilterTransfer = (new ProductOfferCriteriaFilterTransfer())
            ->setIdProductOffer($productOfferTransfer->getIdProductOffer());

        // Act
        $productOfferTransfer = $this->tester->getFacade()->create($productOfferTransfer);
        $storeTransfers = $this->tester->getProductOfferRepository()
            ->findOne($productOfferCriteriaFilterTransfer)
            ->getStores();

        // Assert
        $this->assertEquals($productOfferTransfer->getStores(), $storeTransfers);
    }

    /**
     * @return void
     */
    public function testActivateProductOfferById(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::IS_ACTIVE => false,
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
        ]);

        // Act
        $productOfferTransfer->setIsActive(true);
        $productOfferResponseTransfer = $this->tester->getFacade()->update($productOfferTransfer);

        // Assert
        $this->assertTrue($productOfferResponseTransfer->getIsSuccessful());
        $this->assertTrue($productOfferResponseTransfer->getProductOffer()->getIsActive());
    }

    /**
     * @return void
     */
    public function testDeactivateProductOfferById(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
        ]);

        // Act
        $productOfferTransfer->setIsActive(false);
        $productOfferResponseTransfer = $this->tester->getFacade()->update($productOfferTransfer);

        // Assert
        $this->assertTrue($productOfferResponseTransfer->getIsSuccessful());
        $this->assertFalse($productOfferResponseTransfer->getProductOffer()->getIsActive());
    }

    /**
     * @return void
     */
    public function testFilterInactiveProductOfferItems(): void
    {
        // Arrange
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setStore((new StoreTransfer())->setName('DE'));
        $quoteTransfer->setItems(new ArrayObject([
            (new ItemTransfer())->setProductOfferReference('test1')->setSku('sku1'),
            (new ItemTransfer())->setProductOfferReference('test2')->setSku('sku2'),
        ]));

        $productOfferCollectionTransfer = new ProductOfferCollectionTransfer();
        $productOfferCollectionTransfer->setProductOffers(
            new ArrayObject([
                (new ProductOfferTransfer())->setProductOfferReference('test1'),
            ])
        );

        $productOfferRepositoryMock = $this->getMockBuilder(ProductOfferRepositoryInterface::class)
            ->onlyMethods(['find', 'findOne', 'getProductOfferStores', 'getMaxIdProductOffer', 'isProductOfferReferenceUsed'])
            ->getMock();
        $productOfferRepositoryMock
            ->method('find')
            ->willReturn($productOfferCollectionTransfer);

        $productOfferBusinessFactoryMock = $this->getMockBuilder(ProductOfferBusinessFactory::class)
            ->onlyMethods(['getRepository', 'resolveDependencyProvider'])
            ->getMock();
        $productOfferBusinessFactoryMock
            ->method('getRepository')
            ->willReturn($productOfferRepositoryMock);
        $productOfferBusinessFactoryMock
            ->method('resolveDependencyProvider')
            ->willReturn(
                new ProductOfferDependencyProvider()
            );

        /** @var \Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade $productOfferFacade */
        $productOfferFacade = $this->tester->getFacade();
        $productOfferFacade->setFactory($productOfferBusinessFactoryMock);

        // Act
        $filteredQuoteTransfers = $productOfferFacade->filterInactiveProductOfferItems($quoteTransfer);

        // Assert
        $this->assertCount(1, $filteredQuoteTransfers->getItems());
    }

    /**
     * @return void
     */
    public function testCheckItemProductOfferWithValidProductOfferReturnsSuccess(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $itemTransfer = (new ItemTransfer())
            ->setSku($productOfferTransfer->getConcreteSku())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference());
        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem($itemTransfer)
            ->setQuote((new QuoteTransfer()));

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->checkItemProductOffer($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertEmpty($cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testCheckItemProductOfferWithInValidProductOfferReturnsError(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $productOfferTransfer2 = $this->tester->haveProductOffer();
        $itemTransfer = (new ItemTransfer())
            ->setSku($productOfferTransfer->getConcreteSku())
            ->setProductOfferReference($productOfferTransfer2->getProductOfferReference());
        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem($itemTransfer)
            ->setQuote((new QuoteTransfer()));

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->checkItemProductOffer($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertNotEmpty($cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testCheckItemProductOfferWithoutProductOfferReturnsSuccess(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $itemTransfer = (new ItemTransfer())
            ->setSku($productConcreteTransfer->getSku())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference());
        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem($itemTransfer)
            ->setQuote((new QuoteTransfer()));

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->checkItemProductOffer($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertNotEmpty($cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testUpdateAddsRelationsBetweenProductOffersAndStores(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $storeTransfer = $this->tester->haveStore();
        $this->tester->haveProductOfferStore($productOfferTransfer, $storeTransfer);
        $productOfferTransfer->addStore($storeTransfer);
        $productOfferTransfer->addStore($this->tester->haveStore());
        $productOfferTransfer->addStore($this->tester->haveStore());
        $productOfferCriteriaFilterTransfer = (new ProductOfferCriteriaFilterTransfer())
            ->setIdProductOffer($productOfferTransfer->getIdProductOffer());

        // Act
        $this->tester->getFacade()->update($productOfferTransfer);
        $storeTransfers = $this->tester->getProductOfferRepository()
            ->findOne($productOfferCriteriaFilterTransfer)
            ->getStores();

        // Assert
        $this->assertCount(3, $storeTransfers);
        $this->assertEquals($productOfferTransfer->getStores(), $storeTransfers);
    }

    /**
     * @return void
     */
    public function testUpdateDeletesRelationsBetweenProductOffersAndStores(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $storeTransfer1 = $this->tester->haveStore();
        $storeTransfer2 = $this->tester->haveStore();
        $this->tester->haveProductOfferStore($productOfferTransfer, $storeTransfer1);
        $this->tester->haveProductOfferStore($productOfferTransfer, $storeTransfer2);
        $productOfferTransfer->addStore($storeTransfer1);
        $productOfferCriteriaFilterTransfer = (new ProductOfferCriteriaFilterTransfer())
            ->setIdProductOffer($productOfferTransfer->getIdProductOffer());

        // Act
        $this->tester->getFacade()->update($productOfferTransfer);
        $storeTransfers = $this->tester->getProductOfferRepository()
            ->findOne($productOfferCriteriaFilterTransfer)
            ->getStores();

        // Assert
        $this->assertCount(1, $storeTransfers);
        $this->assertEquals($productOfferTransfer->getStores(), $storeTransfers);
    }

    /**
     * @return void
     */
    public function testGetApplicableMerchantStatusesForApprovedStatus(): void
    {
        // Arrange
        $currentApprovalStatus = 'approved';
        $expectedResult = ['denied'];

        // Act
        $result = $this->tester->getFacade()->getApplicableApprovalStatuses($currentApprovalStatus);

        // Assert
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return void
     */
    public function testGetApplicableMerchantStatusesForDeniedStatus(): void
    {
        // Arrange
        $currentApprovalStatus = 'denied';
        $expectedResult = ['approved'];

        // Act
        $result = $this->tester->getFacade()->getApplicableApprovalStatuses($currentApprovalStatus);

        // Assert
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return void
     */
    public function testGetApplicableMerchantStatusesForWaitingForApprovalStatus(): void
    {
        // Arrange
        $currentApprovalStatus = 'waiting_for_approval';
        $expectedResult = ['approved', 'denied'];

        // Act
        $result = $this->tester->getFacade()->getApplicableApprovalStatuses($currentApprovalStatus);

        // Assert
        $this->assertSame($expectedResult, $result);
    }
}
