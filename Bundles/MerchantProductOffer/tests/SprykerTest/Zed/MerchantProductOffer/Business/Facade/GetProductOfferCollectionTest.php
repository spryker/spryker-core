<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\MerchantProductOption\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantProductOfferConditionsTransfer;
use Generated\Shared\Transfer\MerchantProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use SprykerTest\Zed\MerchantProductOffer\MerchantProductOfferBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group MerchantProductOption
 * @group Business
 * @group Facade
 * @group GetProductOfferCollectionTest
 * Add your own group annotations below this line
 */
class GetProductOfferCollectionTest extends Unit
{
    /**
     * @var int
     */
    protected const NOT_EXISTING_ID_STORE = 0;

    /**
     * @var \SprykerTest\Zed\MerchantProductOffer\MerchantProductOfferBusinessTester
     */
    protected MerchantProductOfferBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetProductOfferCollectionReturnsEmptyCollectionWhileNoCriteriaMatched(): void
    {
        // Arrange
        $this->tester->haveProductOffer();

        $merchantProductOfferConditionsTransfer = (new MerchantProductOfferConditionsTransfer())
            ->addIdStore(static::NOT_EXISTING_ID_STORE);
        $merchantProductOfferCriteriaTransfer = (new MerchantProductOfferCriteriaTransfer())
            ->setMerchantProductOfferConditions($merchantProductOfferConditionsTransfer);

        // Act
        $merchantProductOptionGroupCollectionTransfer = $this->tester->getFacade()
            ->getProductOfferCollection($merchantProductOfferCriteriaTransfer);

        // Assert
        $this->assertCount(0, $merchantProductOptionGroupCollectionTransfer->getProductOffers());
    }

    /**
     * @return void
     */
    public function testGetProductOfferCollectionReturnsCollectionWithOneMerchantProductOptionGroupWhileCriteriasMatched(): void
    {
        // Arrange
        $storeTransfer = $this->tester->getLocator()->store()->facade()->getCurrentStore();
        $merchantTransfer1 = $this->tester->haveMerchant();
        $merchantTransfer2 = $this->tester->haveMerchant();

        $productOfferTransfer1 = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer1->getMerchantReference(),
        ])->addStore($storeTransfer);
        $this->tester->haveProductOfferStore($productOfferTransfer1, $storeTransfer);

        $productOfferTransfer2 = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer2->getMerchantReference(),
        ])->addStore($storeTransfer);
        $this->tester->haveProductOfferStore($productOfferTransfer2, $storeTransfer);

        $merchantProductOfferConditionsTransfer = (new MerchantProductOfferConditionsTransfer())
            ->addMerchantReference($merchantTransfer1->getMerchantReference())
            ->addSku($productOfferTransfer1->getConcreteSku())
            ->addIdStore($storeTransfer->getIdStore())
            ->setIsActive($productOfferTransfer1->getIsActive());
        $merchantProductOfferCriteriaTransfer = (new MerchantProductOfferCriteriaTransfer())
            ->setMerchantProductOfferConditions($merchantProductOfferConditionsTransfer);

        // Act
        $merchantProductOptionGroupCollectionTransfer = $this->tester->getFacade()
            ->getProductOfferCollection($merchantProductOfferCriteriaTransfer);

        // Assert
        $this->assertCount(1, $merchantProductOptionGroupCollectionTransfer->getProductOffers());
        $this->assertSame(
            $productOfferTransfer1->getIdProductOffer(),
            $merchantProductOptionGroupCollectionTransfer->getProductOffers()->getIterator()->current()->getIdProductOffer(),
        );
    }

    /**
     * @return void
     */
    public function testGetProductOfferCollectionReturnsCollectionWithThreeProductOffersWhileHavingLimitOffsetPaginationApplied(): void
    {
        // Arrange
        $this->tester->ensureProductOfferTablesAreEmpty();
        $storeTransfer = $this->tester->getLocator()->store()->facade()->getCurrentStore();
        $merchantTransfer = $this->tester->haveMerchant();

        for ($i = 0; $i < 5; $i++) {
            $productOfferTransfer = $this->tester->haveProductOffer([
                ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            ])->addStore($storeTransfer);
            $this->tester->haveProductOfferStore($productOfferTransfer, $storeTransfer);
        }

        $merchantProductOfferCriteriaTransfer = (new MerchantProductOfferCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setLimit(3)->setOffset(1),
            );

        // Act
        $merchantProductOfferCollectionTransfer = $this->tester->getFacade()
            ->getProductOfferCollection($merchantProductOfferCriteriaTransfer);

        // Assert
        $this->assertCount(3, $merchantProductOfferCollectionTransfer->getProductOffers());
    }
}
