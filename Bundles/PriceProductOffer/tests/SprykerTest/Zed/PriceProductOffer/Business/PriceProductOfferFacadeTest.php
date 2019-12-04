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
    /**
     * @var \SprykerTest\Zed\PriceProductOffer\PriceProductOfferBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetPriceProductConcreteTransfersReturnsProductOfferPricesIfExists(): void
    {
        // Arrange
        $sku = 'sku-1';

        $this->tester->createPriceProductOfferPriceForSku($sku);

        // Act
        $priceProductOfferTransfers = $this->tester->getFacade()
            ->getPriceProductConcreteTransfers([$sku], new PriceProductCriteriaTransfer());

        // Assert
        $this->assertNotEmpty($priceProductOfferTransfers);
    }

    /**
     * @return void
     */
    public function testGetPriceProductConcreteTransfersReturnsNothingIfPricesNotExist(): void
    {
        // Arrange
        $sku = 'sku-1';
        
        // Act
        $priceProductOfferTransfers = $this->tester->getFacade()
            ->getPriceProductConcreteTransfers([$sku], new PriceProductCriteriaTransfer());

        // Assert
        $this->assertNotEmpty($priceProductOfferTransfers);
    }
}
