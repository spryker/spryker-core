<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferSearch\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductAbstractMerchantConditionsTransfer;
use Generated\Shared\Transfer\ProductAbstractMerchantCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductPageSearch\Business\DataMapper\PageMapBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOfferSearch
 * @group Business
 * @group Facade
 * @group Facade
 * @group MerchantProductOfferSearchFacadeTest
 * Add your own group annotations below this line
 */
class MerchantProductOfferSearchFacadeTest extends Unit
{
    /**
     * @uses \Spryker\Zed\MerchantProductOfferSearch\Persistence\Mapper\ProductAbstractMerchantMapper::KEY_MERCHANT_NAMES
     *
     * @var string
     */
    protected const KEY_MERCHANT_NAMES = 'names';

    /**
     * @uses \Spryker\Zed\MerchantProductOfferSearch\Persistence\Mapper\ProductAbstractMerchantMapper::KEY_MERCHANT_REFERENCES
     *
     * @var string
     */
    protected const KEY_MERCHANT_REFERENCES = 'references';

    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_DENIED
     *
     * @var string
     */
    protected const PRODUCT_OFFER_APPROVAL_STATUS_DENIED = 'denied';

    /**
     * @var \SprykerTest\Zed\MerchantProductOfferSearch\MerchantProductOfferSearchCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetProductAbstractMerchantsByProductAbstractIds(): void
    {
        // Arrange
        $expectedResult = $this->tester->haveProductAbstractMerchantData();

        // Act
        $productAbstractMerchantTransfers = $this->tester
            ->getFacade()
            ->getProductAbstractMerchantDataByProductAbstractIds([
                $expectedResult[0]->getIdProductAbstractOrFail(),
                $expectedResult[1]->getIdProductAbstractOrFail(),
            ]);

        // Assert
        $this->assertEquals($productAbstractMerchantTransfers, $expectedResult);
    }

    /**
     * @return void
     */
    public function testGetProductAbstractMerchantsByProductAbstractIdsReturnsEmptyCollectionWhenNoIdsArePassed(): void
    {
        // Arrange
        $this->tester->haveProductAbstractMerchantData();

        // Act
        $productAbstractMerchantTransfers = $this->tester
            ->getFacade()
            ->getProductAbstractMerchantDataByProductAbstractIds([]);

        // Assert
        $this->assertEmpty($productAbstractMerchantTransfers);
    }

    /**
     * @return void
     */
    public function testGetProductAbstractMerchantsByProductAbstractIdsFindsActiveOffersOnly(): void
    {
        // Arrange
        $expectedResult = $this->tester->haveProductAbstractMerchantData(false);

        // Act
        $productAbstractMerchantTransfers = $this->tester
            ->getFacade()
            ->getProductAbstractMerchantDataByProductAbstractIds([
                $expectedResult[0]->getIdProductAbstractOrFail(),
                $expectedResult[1]->getIdProductAbstractOrFail(),
            ]);

        // Assert
        $this->assertCount(1, $productAbstractMerchantTransfers);
        $this->assertEquals($productAbstractMerchantTransfers[0], $expectedResult[1]);
    }

    /**
     * @return void
     */
    public function testGetProductAbstractMerchantsByProductAbstractIdsFindsOffersWithDifferentApprovalStatuses(): void
    {
        // Arrange
        $expectedResult = $this->tester->haveProductAbstractMerchantData(
            true,
            static::PRODUCT_OFFER_APPROVAL_STATUS_DENIED,
        );

        // Act
        $productAbstractMerchantTransfers = $this->tester
            ->getFacade()
            ->getProductAbstractMerchantDataByProductAbstractIds([
                $expectedResult[0]->getIdProductAbstractOrFail(),
                $expectedResult[1]->getIdProductAbstractOrFail(),
            ]);

        // Assert
        $this->assertEquals($productAbstractMerchantTransfers, $expectedResult);
    }

    /**
     * @return void
     */
    public function testExpandProductConcretePageMapSuccess(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $productConcreteTransfer = $this->tester->haveProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);
        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::IS_ACTIVE => true,
        ]);

        $this->tester->haveProductOffer([
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productConcreteTransfer->getIdProductConcrete(),
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
            ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);

        $productData = [
            ProductConcretePageSearchTransfer::SKU => $productConcreteTransfer->getSku(),
            ProductConcretePageSearchTransfer::STORE => $storeTransfer->getName(),
        ];

        // Act
        $pageMapTransfer = $this->tester->getFacade()->expandProductConcretePageMap(
            new PageMapTransfer(),
            new PageMapBuilder(),
            $productData,
            new LocaleTransfer(),
        );

        // Assert
        $this->assertContains($merchantTransfer->getMerchantReference(), $pageMapTransfer->getMerchantReferences());
    }

    /**
     * @return void
     */
    public function testExpandProductConcretePageMapFailed(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);
        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::IS_ACTIVE => true,
        ]);
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productConcreteTransfer->getIdProductConcrete(),
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
            ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
        ]);

        $productOfferStoreTransfer = $this->tester->haveProductOfferStore($productOfferTransfer, $storeTransfer);

        $productData = [
            ProductConcretePageSearchTransfer::SKU => $productConcreteTransfer->getIdProductConcrete(),
            ProductConcretePageSearchTransfer::STORE => $storeTransfer->getName(),
        ];

        // Act
        $pageMapTransfer = $this->tester->getFacade()->expandProductConcretePageMap(
            new PageMapTransfer(),
            new PageMapBuilder(),
            $productData,
            new LocaleTransfer(),
        );

        // Assert
        $this->assertCount(0, $pageMapTransfer->getMerchantReferences());
        $this->assertCount(0, $pageMapTransfer->getFullTextBoosted());
    }

    /**
     * @return void
     */
    public function testGetProductAbstractMerchantCollectionReturnsCorrectDataByProductAbstractIds(): void
    {
        // Arrange
        $expectedResult = $this->tester->haveProductAbstractMerchantData();
        $productAbstractMerchantCriteriaTransfer = (new ProductAbstractMerchantCriteriaTransfer())
            ->setProductAbstractMerchantConditions(
                (new ProductAbstractMerchantConditionsTransfer())->setProductAbstractIds([
                    $expectedResult[0]->getIdProductAbstractOrFail(),
                    $expectedResult[1]->getIdProductAbstractOrFail(),
                ]),
            );

        // Act
        $productAbstractMerchantTransfers = $this->tester
            ->getFacade()
            ->getProductAbstractMerchantCollection($productAbstractMerchantCriteriaTransfer)
            ->getProductAbstractMerchants()
            ->getArrayCopy();

        // Assert
        $this->assertEquals($productAbstractMerchantTransfers, $expectedResult);
    }

    /**
     * @return void
     */
    public function testGetProductAbstractMerchantCollectionReturnsEmptyCollectionWhenNoIdsArePassed(): void
    {
        // Arrange
        $this->tester->haveProductAbstractMerchantData();
        $productAbstractMerchantCriteriaTransfer = (new ProductAbstractMerchantCriteriaTransfer())
            ->setProductAbstractMerchantConditions(
                (new ProductAbstractMerchantConditionsTransfer())->setProductAbstractIds([]),
            );

        // Act
        $productAbstractMerchantTransfers = $this->tester
            ->getFacade()
            ->getProductAbstractMerchantCollection($productAbstractMerchantCriteriaTransfer)
            ->getProductAbstractMerchants()
            ->getArrayCopy();

        // Assert
        $this->assertEmpty($productAbstractMerchantTransfers);
    }

    /**
     * @return void
     */
    public function testGetProductAbstractMerchantCollectionFilteredByOfferIsActive(): void
    {
        // Arrange
        $expectedResult = $this->tester->haveProductAbstractMerchantData(false);
        $productAbstractMerchantCriteriaTransfer = (new ProductAbstractMerchantCriteriaTransfer())
            ->setProductAbstractMerchantConditions(
                (new ProductAbstractMerchantConditionsTransfer())->setProductAbstractIds([
                    $expectedResult[0]->getIdProductAbstractOrFail(),
                    $expectedResult[1]->getIdProductAbstractOrFail(),
                ])->setIsProductOfferActive(false),
            );

        // Act
        $productAbstractMerchantTransfers = $this->tester
            ->getFacade()
            ->getProductAbstractMerchantCollection($productAbstractMerchantCriteriaTransfer)
            ->getProductAbstractMerchants()
            ->getArrayCopy();

        // Assert
        $this->assertCount(1, $productAbstractMerchantTransfers);
        $this->assertEquals($productAbstractMerchantTransfers[0], $expectedResult[0]);
    }

    /**
     * @return void
     */
    public function testGetProductAbstractMerchantCollectionFilteredByApprovalStatus(): void
    {
        // Arrange
        $expectedResult = $this->tester->haveProductAbstractMerchantData(
            true,
            static::PRODUCT_OFFER_APPROVAL_STATUS_DENIED,
        );
        $productAbstractMerchantCriteriaTransfer = (new ProductAbstractMerchantCriteriaTransfer())
            ->setProductAbstractMerchantConditions(
                (new ProductAbstractMerchantConditionsTransfer())->setProductAbstractIds([
                    $expectedResult[0]->getIdProductAbstractOrFail(),
                    $expectedResult[1]->getIdProductAbstractOrFail(),
                ])->addProductOfferApprovalStatus(static::PRODUCT_OFFER_APPROVAL_STATUS_DENIED),
            );

        // Act
        $productAbstractMerchantTransfers = $this->tester
            ->getFacade()
            ->getProductAbstractMerchantCollection($productAbstractMerchantCriteriaTransfer)
            ->getProductAbstractMerchants()
            ->getArrayCopy();

        // Assert
        $this->assertCount(1, $productAbstractMerchantTransfers);
        $this->assertEquals($productAbstractMerchantTransfers[0], $expectedResult[0]);
    }
}
