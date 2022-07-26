<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMerchantPortalGui\Communication\Reader;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductMerchantPortalGui
 * @group Communication
 * @group Reader
 * @group PriceProductReaderTest
 * Add your own group annotations below this line
 */
class PriceProductReaderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetPriceProductsWithoutPriceExtractionCallsPriceProductFacadeWithOnlyConcretePricesAndWithAllMerchantPricesParams(): void
    {
        // Arrange
        $idProductConcrete = 1;
        $idProductAbstract = 2;

        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())
            ->setIdProductConcrete($idProductConcrete)
            ->setIdProductAbstract($idProductAbstract);
        $priceProductFacadeMock = $this->tester->createPriceProductFacadeMockWithExpectations(
            $this->once(),
            'findProductConcretePricesWithoutPriceExtraction',
            $idProductConcrete,
            $idProductAbstract,
            (new PriceProductCriteriaTransfer())
                ->setOnlyConcretePrices(true)
                ->setWithAllMerchantPrices(true),
        );

        $priceProductReader = $this->tester->createPriceProductReader($priceProductFacadeMock);

        // Act, Assert
        $priceProductReader->getPriceProductsWithoutPriceExtraction($priceProductCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testGetPriceProductsWithoutPriceExtractionCallsPriceProductFacadeWithAllMerchantPricesParam(): void
    {
        // Arrange
        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())->setIdProductAbstract(2);

        $priceProductFacadeMock = $this->tester->createPriceProductFacadeMockWithExpectations(
            $this->once(),
            'findProductAbstractPricesWithoutPriceExtraction',
            $priceProductCriteriaTransfer->getIdProductAbstractOrFail(),
            (new PriceProductCriteriaTransfer())->setWithAllMerchantPrices(true),
        );

        $priceProductReader = $this->tester->createPriceProductReader($priceProductFacadeMock);

        // Act, Assert
        $priceProductReader->getPriceProductsWithoutPriceExtraction($priceProductCriteriaTransfer);
    }
}
