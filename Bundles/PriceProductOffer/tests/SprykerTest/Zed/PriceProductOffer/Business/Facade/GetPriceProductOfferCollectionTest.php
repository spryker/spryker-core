<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductOffer\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use SprykerTest\Zed\PriceProductOffer\PriceProductOfferBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductOffer
 * @group Business
 * @group Facade
 * @group GetPriceProductOfferCollectionTest
 * Add your own group annotations below this line
 */
class GetPriceProductOfferCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProductOffer\PriceProductOfferBusinessTester
     */
    protected PriceProductOfferBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetPriceProductOfferCollectionWithFivePriceProductOffersWhileHavingLimitOffsetPaginationApplied(): void
    {
        // Arrange
        for ($i = 0; $i < 4; $i++) {
            $this->tester->havePriceProductSaved([PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku' . $i]);
        }

        $priceProductOfferCriteriaTransfer = (new PriceProductOfferCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setLimit(2)->setOffset(1),
            );

        // Act
        $priceProductOfferCollectionTransfer = $this->tester->getFacade()->getPriceProductOfferCollection($priceProductOfferCriteriaTransfer);

        // Assert
        $this->assertCount(2, $priceProductOfferCollectionTransfer->getPriceProductOffers());
    }
}
