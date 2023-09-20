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
 * @group ExpandProductConcreteTransfersWithPricesTest
 * Add your own group annotations below this line
 */
class ExpandProductConcreteTransfersWithPricesTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testExpandProductConcreteTransfersWithPricesSuccessful(): void
    {
        // Arrange
        $productConcreteTransfer1 = $this->tester->haveProduct();
        $this->tester->createPriceProductForAbstractProduct(
            $productConcreteTransfer1->getAbstractSku(),
            $productConcreteTransfer1->getFkProductAbstract(),
        );

        $productConcreteTransfer2 = $this->tester->haveProduct();
        $priceProductTransfer = $this->tester->createPriceProductForConcreteProduct(
            $productConcreteTransfer2->getAbstractSku(),
            $productConcreteTransfer2->getIdProductConcrete(),
        );

        // Act
        $productConcreteTransfers = $this->tester->getFacade()->expandProductConcreteTransfersWithPrices(
            [$productConcreteTransfer1, $productConcreteTransfer2],
        );

        // Assert
        $this->assertSame(0, $productConcreteTransfers[0]->getPrices()->count());

        $this->assertGreaterThan(0, $productConcreteTransfers[1]->getPrices()->count());

        $resultPriceProductTransfer = $productConcreteTransfers[1]->getPrices()->offsetGet(0);
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
}
