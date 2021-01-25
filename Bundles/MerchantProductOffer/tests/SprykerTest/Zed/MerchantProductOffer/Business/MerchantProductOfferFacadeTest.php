<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOffer\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOffer
 * @group Business
 * @group Facade
 * @group MerchantProductOfferFacadeTest
 * Add your own group annotations below this line
 */
class MerchantProductOfferFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductOffer\MerchantProductOfferBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetProductOfferCollectionReturnsFilledCollection(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();

        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
        ]);

        $merchantProductOfferCriteriaFilterTransfer = (new MerchantProductOfferCriteriaFilterTransfer())
            ->setMerchantReference($merchantTransfer->getMerchantReference())
            ->setSkus([$productOfferTransfer->getConcreteSku()])
            ->setIsActive(true);

        // Act
        $productOfferCollectionTransfer = $this->tester->getFacade()->getProductOfferCollection($merchantProductOfferCriteriaFilterTransfer);

        // Assert
        $this->assertNotEmpty($productOfferCollectionTransfer->getProductOffers());
    }
}
