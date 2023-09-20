<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceTypeTransfer;
use SprykerTest\Zed\PriceProduct\PriceProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Facade
 * @group PersistProductConcretePriceCollectionTest
 * Add your own group annotations below this line
 */
class PersistProductConcretePriceCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testPersistProductConcretePriceCollectionShouldSavePriceCollection(): void
    {
        // Arrange
        $priceProductFacade = $this->tester->getFacade();

        $priceTypeTransfer = new PriceTypeTransfer();
        $priceTypeTransfer->setName($priceProductFacade->getDefaultPriceTypeName());

        $productConcreteTransfer = $this->tester->haveProduct();

        $prices = new ArrayObject();
        $prices[] = $this->tester->createPriceProductTransfer($productConcreteTransfer, $priceTypeTransfer, 10, 9, PriceProductBusinessTester::EUR_ISO_CODE);

        $productConcreteTransfer->setPrices($prices);

        // Act
        $productConcreteTransfer = $priceProductFacade->persistProductConcretePriceCollection($productConcreteTransfer);

        // Assert
        foreach ($productConcreteTransfer->getPrices() as $priceProductTransfer) {
            $this->assertNotEmpty($priceProductTransfer->getIdPriceProduct());
            $this->assertNotEmpty($priceProductTransfer->getMoneyValue()->getIdEntity());
        }
    }
}
