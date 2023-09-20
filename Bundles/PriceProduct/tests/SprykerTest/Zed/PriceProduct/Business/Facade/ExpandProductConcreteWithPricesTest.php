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
 * @group ExpandProductConcreteWithPricesTest
 * Add your own group annotations below this line
 */
class ExpandProductConcreteWithPricesTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testExpandProductConcreteWithPricesWillAddConcreteProductPricesWhenTheyAreDefinedForConcreteProduct(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceProductTransfer = $this->tester->createPriceProductForConcreteProduct(
            $productConcreteTransfer->getAbstractSku(),
            $productConcreteTransfer->getIdProductConcrete(),
        );

        // Act
        $productConcreteTransfer = $this->tester->getFacade()->expandProductConcreteWithPrices($productConcreteTransfer);

        // Assert
        $this->assertGreaterThan(0, $productConcreteTransfer->getPrices()->count());

        $resultPriceProductTransfer = $productConcreteTransfer->getPrices()->offsetGet(0);
        $this->assertSame($priceProductTransfer->getIdProduct(), $resultPriceProductTransfer->getIdProduct());
        $this->assertSame(
            $priceProductTransfer->getMoneyValue()->getNetAmount(),
            $resultPriceProductTransfer->getMoneyValue()->getNetAmount(),
        );
        $this->assertSame(
            $priceProductTransfer->getMoneyValue()->getGrossAmount(),
            $resultPriceProductTransfer->getMoneyValue()->getGrossAmount(),
        );
    }

    /**
     * @return void
     */
    public function testExpandProductConcreteWithPricesWillNotAddConcreteProductPricesWhenTheyAreDefinedOnlyForAbstractProduct(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->createPriceProductForAbstractProduct(
            $productConcreteTransfer->getAbstractSku(),
            $productConcreteTransfer->getFkProductAbstract(),
        );

        // Act
        $productConcreteTransfer = $this->tester->getFacade()->expandProductConcreteWithPrices($productConcreteTransfer);

        // Assert
        $this->assertSame(0, $productConcreteTransfer->getPrices()->count());
    }
}
