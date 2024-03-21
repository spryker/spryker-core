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
 * @group SetPriceForProductTest
 * Add your own group annotations below this line
 */
class SetPriceForProductTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testSetPriceForProductShouldUpdateExistingPrice(): void
    {
        if ($this->tester->isDynamicStoreEnabled()) {
            $this->tester->markTestSkipped('Facade method is used for table drawing and not used with Dynamic Store ON');
        }

        // Arrange
        $priceProductFacade = $this->tester->getFacade();

        $priceProductTransfer = $this->tester->createProductWithAmount(50, 40);
        $priceProductTransfer->getMoneyValue()->setGrossAmount(100);

        $priceProductFacade->setPriceForProduct($priceProductTransfer);

        // Act
        $price = $priceProductFacade->findPriceBySku($priceProductTransfer->getSkuProduct());

        // Assert
        $this->assertSame(100, $price);
    }
}
