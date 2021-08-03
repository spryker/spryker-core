<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\PriceProductMerchantRelationship;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\PriceProductBuilder;
use Generated\Shared\DataBuilder\PriceProductFilterBuilder;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group PriceProductMerchantRelationship
 * @group PriceProductMerchantRelationshipServiceTest
 * Add your own group annotations below this line
 */
class PriceProductMerchantRelationshipServiceTest extends Unit
{
    /**
     * @var \SprykerTest\Service\PriceProductMerchantRelationship\PriceProductMerchantRelationshipTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFilterPriceProductsByMerchantRelationshipWillFilterOutMerchantPrices(): void
    {
        // Arrange
        $priceProductTransferMerchant1 = (new PriceProductBuilder())
            ->withPriceDimension([PriceProductDimensionTransfer::ID_MERCHANT_RELATIONSHIP => 1])
            ->build();
        $priceProductTransferMerchant2 = (new PriceProductBuilder())
            ->withPriceDimension([PriceProductDimensionTransfer::ID_MERCHANT_RELATIONSHIP => 2])
            ->build();

        $priceProductFilterTransfer = (new PriceProductFilterBuilder())
            ->withPriceDimension([PriceProductDimensionTransfer::ID_MERCHANT_RELATIONSHIP => 1])
            ->build();

        // Act
        $priceProductTransfers = $this->tester->getService()->filterPriceProductsByMerchantRelationship(
            [$priceProductTransferMerchant1, $priceProductTransferMerchant2],
            $priceProductFilterTransfer
        );

        // Assert
        $this->assertCount(1, $priceProductTransfers);
        $this->assertSame(1, $priceProductTransfers[0]->getPriceDimension()->getIdMerchantRelationship());
    }

    /**
     * @return void
     */
    public function testFilterPriceProductsByMerchantRelationshipWillNotFilterOutPricesWithoutMerchantReference(): void
    {
        // Arrange
        $priceProductTransferMerchant1 = (new PriceProductBuilder())
            ->withPriceDimension([PriceProductDimensionTransfer::ID_MERCHANT_RELATIONSHIP => null])
            ->build();
        $priceProductTransferMerchant2 = (new PriceProductBuilder())
            ->withPriceDimension([PriceProductDimensionTransfer::ID_MERCHANT_RELATIONSHIP => null])
            ->build();

        $priceProductFilterTransfer = (new PriceProductFilterBuilder())
            ->withPriceDimension([PriceProductDimensionTransfer::ID_MERCHANT_RELATIONSHIP => 1])
            ->build();

        // Act
        $priceProductTransfers = $this->tester->getService()->filterPriceProductsByMerchantRelationship(
            [$priceProductTransferMerchant1, $priceProductTransferMerchant2],
            $priceProductFilterTransfer
        );

        // Assert
        $this->assertCount(2, $priceProductTransfers);
    }

    /**
     * @return void
     */
    public function testFilterPriceProductsByMerchantRelationshipWillNotFilterOutMerchantPricesIfPriceProductFilterDoesNotHaveMerchantReference(): void
    {
        // Arrange
        $priceProductTransferMerchant1 = (new PriceProductBuilder())
            ->withPriceDimension([PriceProductDimensionTransfer::ID_MERCHANT_RELATIONSHIP => 1])
            ->build();
        $priceProductTransferMerchant2 = (new PriceProductBuilder())
            ->withPriceDimension([PriceProductDimensionTransfer::ID_MERCHANT_RELATIONSHIP => 2])
            ->build();

        $priceProductFilterTransfer = (new PriceProductFilterBuilder())
            ->withPriceDimension([PriceProductDimensionTransfer::ID_MERCHANT_RELATIONSHIP => null])
            ->build();

        // Act
        $priceProductTransfers = $this->tester->getService()->filterPriceProductsByMerchantRelationship(
            [$priceProductTransferMerchant1, $priceProductTransferMerchant2],
            $priceProductFilterTransfer
        );

        // Assert
        $this->assertCount(2, $priceProductTransfers);
    }
}
