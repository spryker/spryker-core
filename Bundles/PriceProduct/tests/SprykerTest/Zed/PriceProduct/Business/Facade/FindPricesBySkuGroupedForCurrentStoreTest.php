<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Spryker\Shared\Price\PriceConfig;
use SprykerTest\Zed\PriceProduct\PriceProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Facade
 * @group FindPricesBySkuGroupedForCurrentStoreTest
 * Add your own group annotations below this line
 */
class FindPricesBySkuGroupedForCurrentStoreTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testFindPricesBySkuGroupedShouldReturnGroupedPrices(): void
    {
        if ($this->tester->isDynamicStoreEnabled()) {
            $this->markTestSkipped('Current store is only available via gateway call or if there is a get param, that is not the case for this facade method');
        }

        // Arrange
        $priceProductFacade = $this->tester->getFacade();

        $defaultPriceMode = $priceProductFacade->getDefaultPriceTypeName();
        $priceTypeTransfer = new PriceTypeTransfer();
        $priceTypeTransfer->setName($defaultPriceMode);

        $productConcreteTransfer = $this->tester->haveProduct();

        $prices = new ArrayObject();
        $prices[] = $this->tester->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 10, 9, PriceProductBusinessTester::EUR_ISO_CODE);
        $prices[] = $this->tester->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 10, 9, PriceProductBusinessTester::USD_ISO_CODE);

        $productConcreteTransfer->setPrices($prices);

        $productConcreteTransfer = $priceProductFacade->persistProductConcretePriceCollection($productConcreteTransfer);

        // Act
        $storePrices = $priceProductFacade->findPricesBySkuGroupedForCurrentStore($productConcreteTransfer->getSku());

        // Assert
        $this->assertCount(2, $storePrices);

        $this->assertArrayHasKey(PriceProductBusinessTester::EUR_ISO_CODE, $storePrices);
        $this->assertArrayHasKey(PriceProductBusinessTester::USD_ISO_CODE, $storePrices);

        $this->assertArrayHasKey(PriceConfig::PRICE_MODE_GROSS, $storePrices[PriceProductBusinessTester::EUR_ISO_CODE]);
        $this->assertArrayHasKey(PriceConfig::PRICE_MODE_NET, $storePrices[PriceProductBusinessTester::EUR_ISO_CODE]);
        $this->assertArrayHasKey(PriceConfig::PRICE_MODE_GROSS, $storePrices[PriceProductBusinessTester::USD_ISO_CODE]);
        $this->assertArrayHasKey(PriceConfig::PRICE_MODE_NET, $storePrices[PriceProductBusinessTester::USD_ISO_CODE]);

        $this->assertArrayHasKey($defaultPriceMode, $storePrices[PriceProductBusinessTester::USD_ISO_CODE][PriceConfig::PRICE_MODE_GROSS]);
        $this->assertArrayHasKey($defaultPriceMode, $storePrices[PriceProductBusinessTester::USD_ISO_CODE][PriceConfig::PRICE_MODE_NET]);

        $priceGross = $storePrices[PriceProductBusinessTester::USD_ISO_CODE][PriceConfig::PRICE_MODE_GROSS][$defaultPriceMode];
        $priceNet = $storePrices[PriceProductBusinessTester::USD_ISO_CODE][PriceConfig::PRICE_MODE_NET][$defaultPriceMode];

        $this->assertSame(9, $priceGross);
        $this->assertSame(10, $priceNet);
    }
}
