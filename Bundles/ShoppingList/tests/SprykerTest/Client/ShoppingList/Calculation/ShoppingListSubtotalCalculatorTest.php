<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ShoppingList\Calculation;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ShoppingList\Calculation\ShoppingListSubtotalCalculator;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ShoppingList
 * @group Calculation
 * @group ShoppingListSubtotalCalculatorTest
 * Add your own group annotations below this line
 */
class ShoppingListSubtotalCalculatorTest extends Unit
{
    /**
     * @var \Spryker\Client\ShoppingList\Calculation\ShoppingListSubtotalCalculatorInterface
     */
    protected $shoppingListSubtotalCalculator;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->shoppingListSubtotalCalculator = new ShoppingListSubtotalCalculator();
    }

    /**
     * @return void
     */
    public function testCalculateShoppingListSubtotalShouldCalculatePricesCorrectly(): void
    {
        // Arrange
        $shoppingListItemProductViews = [
            (new ProductViewTransfer())
                ->setCurrentProductPrice(
                    $this->createCurrentProductPriceTransferWithSumPrice(1)
                ),
            (new ProductViewTransfer())
                ->setCurrentProductPrice(
                    $this->createCurrentProductPriceTransferWithSumPrice(2)
                ),
        ];

        $expectedShoppingListSubtotal = 3;

        // Act
        $calculatedShoppingListSubtotal = $this->shoppingListSubtotalCalculator->calculateShoppingListSubtotal($shoppingListItemProductViews);

        // Assert
        $this->assertSame($calculatedShoppingListSubtotal, $expectedShoppingListSubtotal);
    }

    /**
     * @return void
     */
    public function testCalculateShoppingListSubtotalShouldThrowExceptionIfCurrentPriceProductIsNotDefined(): void
    {
        // Arrange
        $shoppingListItemProductViews = [
            new ProductViewTransfer(),
        ];

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->shoppingListSubtotalCalculator->calculateShoppingListSubtotal($shoppingListItemProductViews);
    }

    /**
     * @param int $sumPrice
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    protected function createCurrentProductPriceTransferWithSumPrice(int $sumPrice): CurrentProductPriceTransfer
    {
        return (new CurrentProductPriceTransfer())->setSumPrice($sumPrice);
    }
}
