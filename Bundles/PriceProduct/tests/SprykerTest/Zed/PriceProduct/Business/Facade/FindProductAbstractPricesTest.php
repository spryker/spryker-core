<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use SprykerTest\Zed\PriceProduct\PriceProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Facade
 * @group FindProductAbstractPricesTest
 * Add your own group annotations below this line
 */
class FindProductAbstractPricesTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testFindProductAbstractPricesShouldReturnPriceAssignedToAbstractProduct(): void
    {
        // Arrange
        $priceProductFacade = $this->tester->getFacade();

        $priceTypeTransfer = new PriceTypeTransfer();
        $priceTypeTransfer->setName($priceProductFacade->getDefaultPriceTypeName());

        $productConcreteTransfer = $this->tester->haveProduct();

        $prices = new ArrayObject();
        $prices[] = $this->tester->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 10, 9, PriceProductBusinessTester::EUR_ISO_CODE);
        $prices[] = $this->tester->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 11, 10, PriceProductBusinessTester::USD_ISO_CODE);

        $productAbstractTransfer = (new ProductAbstractTransfer())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setSku($productConcreteTransfer->getAbstractSku())
            ->setPrices($prices);

        $productAbstractTransfer = $priceProductFacade->persistProductAbstractPriceCollection($productAbstractTransfer);

        // Act
        $storedPrices = $priceProductFacade->findProductAbstractPrices(
            $productAbstractTransfer->getIdProductAbstract(),
            $this->tester->createPriceProductCriteriaTransfer(),
        );

        // Assert
        $this->assertCount(2, $storedPrices);
    }
}
