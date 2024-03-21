<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\StoreTransfer;
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
     * @var string
     */
    protected const DEFAULT_STORE = 'DE';

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
            ->setSku($priceProductTransfer->getSkuProduct())
            ->setStoreName($this->tester->haveStore([StoreTransfer::NAME => static::DEFAULT_STORE])->getName());

        // Act
        $price = $this->tester->getFacade()->findPriceFor($priceProductFilterTransfer);

        // Assert
        $this->assertSame(100, $price);
    }
}
