<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use SprykerTest\Zed\PriceProduct\PriceProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Facade
 * @group MergeProductAbstractPricesIntoProductConcreteTest
 * Add your own group annotations below this line
 */
class MergeProductAbstractPricesIntoProductConcreteTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testMergeProductAbstractPricesIntoProductConcreteTakenFromProductAbstractIfEmpty(): void
    {
        // Arrange
        $priceTypeTransfer = new PriceTypeTransfer();
        $productConcrete = new ProductConcreteTransfer();

        $productAbstractPrice = $this->tester->createPriceProductTransfer(
            $productConcrete,
            $priceTypeTransfer,
            10,
            9,
            PriceProductBusinessTester::EUR_ISO_CODE,
        )->toArray();

        $productAbstract = (new ProductAbstractTransfer())->fromArray(['prices' => [$productAbstractPrice]]);

        // Act
        $productConcreteResult = $this->tester->getFacade()->mergeProductAbstractPricesIntoProductConcrete(
            $productConcrete,
            $productAbstract,
        );

        // Assert
        $this->assertEquals($productAbstractPrice, $productConcreteResult->getPrices()[0]->toArray());
    }

    /**
     * @return void
     */
    public function testMergeProductAbstractPricesIntoProductConcretePricesNoTakenFromProductAbstractIfExist(): void
    {
        // Arrange
        $priceTypeTransfer = new PriceTypeTransfer();
        $productConcrete = new ProductConcreteTransfer();

        $productAbstractPrice = $this->tester->createPriceProductTransfer(
            $productConcrete,
            $priceTypeTransfer,
            10,
            9,
            PriceProductBusinessTester::EUR_ISO_CODE,
        )->toArray();

        $productConcretePrice = $this->tester->createPriceProductTransfer(
            $productConcrete,
            $priceTypeTransfer,
            20,
            12,
            PriceProductBusinessTester::EUR_ISO_CODE,
        )->toArray();

        $productConcrete = $productConcrete->fromArray(['prices' => [$productConcretePrice]]);
        $productAbstract = (new ProductAbstractTransfer())->fromArray(['prices' => [$productAbstractPrice]]);

        // Act
        $productConcreteResult = $this->tester->getFacade()->mergeProductAbstractPricesIntoProductConcrete(
            $productConcrete,
            $productAbstract,
        );

        // Assert
        $this->assertEquals($productConcretePrice, $productConcreteResult->getPrices()[0]->toArray());
    }
}
