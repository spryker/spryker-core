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
 * @group FindPriceForTest
 * Add your own group annotations below this line
 */
class FindPriceForTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testFindPriceForShouldReturnPriceBasedOnFilter(): void
    {
        // Arrange
        $priceProductTransfer = $this->tester->createProductWithAmount(
            100,
            90,
            '',
            '',
            PriceProductBusinessTester::USD_ISO_CODE,
        );

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setCurrencyIsoCode(PriceProductBusinessTester::USD_ISO_CODE)
            ->setSku($priceProductTransfer->getSkuProduct());

        // Act
        $price = $this->tester->getFacade()->findPriceFor($priceProductFilterTransfer);

        // Assert
        $this->assertSame(100, $price);
    }
}
