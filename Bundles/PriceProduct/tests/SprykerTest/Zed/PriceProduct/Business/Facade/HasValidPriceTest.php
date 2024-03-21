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
 * @group HasValidPriceTest
 * Add your own group annotations below this line
 */
class HasValidPriceTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testHasValidPriceShouldReturnTrueWhenProductHavePrices(): void
    {
        if ($this->tester->isDynamicStoreEnabled()) {
            $this->tester->markTestSkipped('This facade method is unused and not working with Dynamic Store ON');
        }

        // Arrange
        $priceProductTransfer = $this->tester->createProductWithAmount(50, 40);

        // Act
        $result = $this->tester->getFacade()->hasValidPrice($priceProductTransfer->getSkuProduct());

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testHasValidPriceForReturnTrueWhenProductHavePrices(): void
    {
        /*
         * Facade method is used in context of {@link \Spryker\Zed\Cart\Communication\Controller\GatewayController::addToCartAction}
         */
        $this->tester->addCurrentStore($this->tester->haveStore([StoreTransfer::NAME => 'DE']));

        // Arrange
        $priceProductTransfer = $this->tester->createProductWithAmount(50, 40);

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setSku($priceProductTransfer->getSkuProduct());

        // Act
        $result = $this->tester->getFacade()->hasValidPriceFor($priceProductFilterTransfer);

        // Assert
        $this->assertTrue($result);
    }
}
