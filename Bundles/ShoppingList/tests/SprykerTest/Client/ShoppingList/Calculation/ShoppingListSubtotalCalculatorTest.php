<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ShoppingList\Calculation;

use Codeception\Test\Unit;
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
    protected const KEY_PRICE = 'price';
    protected const KEY_QUANTITY = 'quantity';

    /**
     * @var \SprykerTest\Client\ShoppingList\ShoppingListClientTester
     */
    protected $tester;

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
        $shoppingListItems = [
            [ static::KEY_PRICE => 1, static::KEY_QUANTITY => 1 ],
            [ static::KEY_PRICE => 2, static::KEY_QUANTITY => 2 ],
            [ static::KEY_PRICE => 3, static::KEY_QUANTITY => 3 ],
            [ static::KEY_PRICE => 4, static::KEY_QUANTITY => 4 ],
            [ static::KEY_PRICE => 5, static::KEY_QUANTITY => 5 ],
        ];

        $expectedShoppingListSubtotal = 55;

        $this->assertSame(
            $this->shoppingListSubtotalCalculator->calculateShoppingListSubtotal($shoppingListItems),
            $expectedShoppingListSubtotal
        );
    }

    /**
     * @return void
     */
    public function testCalculateShoppingListSubtotalShouldSkipItemsWithoutPriceOrQuantityDuringSubtotalCalculation(): void
    {
        $shoppingListItems = [
            [ static::KEY_PRICE => null, static::KEY_QUANTITY => 1 ],
            [ static::KEY_PRICE => 2, static::KEY_QUANTITY => 2 ],
            [ static::KEY_PRICE => 3, static::KEY_QUANTITY => null ],
            [ static::KEY_PRICE => 4, static::KEY_QUANTITY => 4 ],
            [ static::KEY_PRICE => null, static::KEY_QUANTITY => null ],
        ];

        $expectedShoppingListSubtotal = 20;

        $this->assertSame(
            $this->shoppingListSubtotalCalculator->calculateShoppingListSubtotal($shoppingListItems),
            $expectedShoppingListSubtotal
        );
    }
}
