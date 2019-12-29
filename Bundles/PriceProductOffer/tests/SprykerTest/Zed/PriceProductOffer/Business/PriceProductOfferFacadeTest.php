<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductOffer\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductOffer
 * @group Business
 * @group Facade
 * @group PriceProductOfferFacadeTest
 * Add your own group annotations below this line
 */
class PriceProductOfferFacadeTest extends Unit
{
    protected const NOT_EXISTING_SKU = 'non-existing-sku';

    /**
     * @var \SprykerTest\Zed\PriceProductOffer\PriceProductOfferBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetPriceProductTransfersReturnsNothingIfPricesNotExist(): void
    {
        // Act
        $priceProductOfferTransfers = $this->tester->getFacade()
            ->getPriceProductTransfers(
                [static::NOT_EXISTING_SKU],
                new PriceProductCriteriaTransfer()
            );

        // Assert
        $this->assertEmpty($priceProductOfferTransfers);
    }

    /**
     * @return void
     */
    public function testGetPriceProductTransfersReturnsProductOfferPricesIfExists(): void
    {
        // Arrange
        $priceProductOfferTransfer = $this->tester->havePriceProductOffer();

        // Act
        $priceProductOfferTransfers = $this->tester->getFacade()
            ->getPriceProductTransfers(
                [
                    $priceProductOfferTransfer->getProductOffer()->getConcreteSku(),
                ],
                (new PriceProductCriteriaTransfer())
                    ->setIdCurrency($priceProductOfferTransfer->getFkCurrency())
            );

        // Assert
        $this->assertNotEmpty($priceProductOfferTransfers);
    }
}
