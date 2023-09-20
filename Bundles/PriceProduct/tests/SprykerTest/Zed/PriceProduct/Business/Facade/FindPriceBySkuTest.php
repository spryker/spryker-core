<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Facade;

use Codeception\Test\Unit;
use SprykerTest\Zed\PriceProduct\PriceProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Facade
 * @group FindPriceBySkuTest
 * Add your own group annotations below this line
 */
class FindPriceBySkuTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testFindPriceBySkuShouldReturnDefaultPriceForGivenProduct(): void
    {
        // Arrange
        $priceProductTransfer = $this->tester->createProductWithAmount(50, 40);

        // Act
        $price = $this->tester->getFacade()->findPriceBySku($priceProductTransfer->getSkuProduct());

        // Assert
        $this->assertSame(50, $price);
    }
}
