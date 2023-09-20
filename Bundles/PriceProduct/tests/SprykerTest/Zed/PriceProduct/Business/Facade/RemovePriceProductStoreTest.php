<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use SprykerTest\Zed\PriceProduct\PriceProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Facade
 * @group RemovePriceProductStoreTest
 * Add your own group annotations below this line
 */
class RemovePriceProductStoreTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testRemovePriceProductStoreShouldDeletePriceFromDatabase(): void
    {
        // Arrange
        $priceProductFacade = $this->tester->getFacade();

        $priceProductTransfer = $this->tester->createProductWithAmount(
            100,
            90,
            '',
            '',
            PriceProductBusinessTester::EUR_ISO_CODE,
        );

        // Act
        $priceProductFacade->removePriceProductStore($priceProductTransfer);

        // Assert
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setCurrencyIsoCode(PriceProductBusinessTester::EUR_ISO_CODE)
            ->setSku($priceProductTransfer->getSkuProduct());

        $priceProduct = $priceProductFacade->findPriceProductFor($priceProductFilterTransfer);

        $this->assertNull($priceProduct, 'Price product should be removed from db');
    }
}
