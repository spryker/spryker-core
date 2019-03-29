<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ShoppingList\Calculation;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ShoppingList\Calculation\ShoppingListSubtotalCalculator;

/**
 * Auto-generated group annotations
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
            (new ProductViewTransfer())->setAvailable(true)->setPrice(1)->setQuantity(1),
            (new ProductViewTransfer())->setAvailable(true)->setPrice(2)->setQuantity(2),
            (new ProductViewTransfer())->setAvailable(true)->setPrice(3)->setQuantity(3),
            (new ProductViewTransfer())->setAvailable(true)->setPrice(4)->setQuantity(4),
            (new ProductViewTransfer())->setAvailable(true)->setPrice(5)->setQuantity(5),
        ];

        $expectedShoppingListSubtotal = 55;

        // Act
        $calculatedShoppingListSubtotal = $this->shoppingListSubtotalCalculator->calculateShoppingListSubtotal($shoppingListItemProductViews);

        // Assert
        $this->assertSame($calculatedShoppingListSubtotal, $expectedShoppingListSubtotal);
    }

    /**
     * @return void
     */
    public function testCalculateShoppingListSubtotalShouldSkipItemsWithoutPriceOrQuantity(): void
    {
        // Arrange
        $shoppingListItemProductViews = [
            (new ProductViewTransfer())->setAvailable(true)->setPrice(null)->setQuantity(1),
            (new ProductViewTransfer())->setAvailable(true)->setPrice(2)->setQuantity(2),
            (new ProductViewTransfer())->setAvailable(true)->setPrice(3)->setQuantity(null),
            (new ProductViewTransfer())->setAvailable(true)->setPrice(4)->setQuantity(4),
            (new ProductViewTransfer())->setAvailable(true)->setPrice(null)->setQuantity(null),
        ];

        $expectedShoppingListSubtotal = 20;

        // Act
        $calculatedShoppingListSubtotal = $this->shoppingListSubtotalCalculator->calculateShoppingListSubtotal($shoppingListItemProductViews);

        // Assert
        $this->assertSame($calculatedShoppingListSubtotal, $expectedShoppingListSubtotal);
    }

    /**
     * @return void
     */
    public function testCalculateShoppingListSubtotalShouldSkipUnavailableItems(): void
    {
        // Arrange
        $shoppingListItemProductViews = [
            (new ProductViewTransfer())->setAvailable(true)->setPrice(1)->setQuantity(1),
            (new ProductViewTransfer())->setAvailable(false)->setPrice(2)->setQuantity(2),
            (new ProductViewTransfer())->setAvailable(false)->setPrice(3)->setQuantity(3),
            (new ProductViewTransfer())->setAvailable(false)->setPrice(4)->setQuantity(4),
            (new ProductViewTransfer())->setAvailable(false)->setPrice(5)->setQuantity(5),
        ];

        $expectedShoppingListSubtotal = 1;

        // Act
        $calculatedShoppingListSubtotal = $this->shoppingListSubtotalCalculator->calculateShoppingListSubtotal($shoppingListItemProductViews);

        // Assert
        $this->assertSame($calculatedShoppingListSubtotal, $expectedShoppingListSubtotal);
    }
}
