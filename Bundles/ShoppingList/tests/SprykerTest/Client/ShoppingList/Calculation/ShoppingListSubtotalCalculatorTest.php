<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ShoppingList\Calculation;

use Codeception\Test\Unit;
use Spryker\Client\ShoppingList\Calculation\ShoppingListSubtotalCalculator;
use Spryker\Client\ShoppingList\Calculation\ShoppingListSubtotalCalculatorInterface;

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

        $this->assertSame(
            $this->createShoppingListSubtotalCalculator()->calculateShoppingListSubtotal($shoppingListItems),
            $this->getShoppingListSubtotalCalculatorMock()->calculateShoppingListSubtotal($shoppingListItems)
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

        $this->assertSame(
            $this->createShoppingListSubtotalCalculator()->calculateShoppingListSubtotal($shoppingListItems),
            $this->getShoppingListSubtotalCalculatorMock()->calculateShoppingListSubtotal($shoppingListItems)
        );
    }

    /**
     * @return \Spryker\Client\ShoppingList\Calculation\ShoppingListSubtotalCalculatorInterface
     */
    protected function createShoppingListSubtotalCalculator(): ShoppingListSubtotalCalculatorInterface
    {
        return new ShoppingListSubtotalCalculator();
    }

    /**
     * @return \Spryker\Client\ShoppingList\Calculation\ShoppingListSubtotalCalculatorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getShoppingListSubtotalCalculatorMock()
    {
        $shoppingListSubtotalCalculatorMock = $this->getMockBuilder(ShoppingListSubtotalCalculatorInterface::class)->getMock();
        $shoppingListSubtotalCalculatorMock->method('calculateShoppingListSubtotal')->willReturnCallback(function ($shoppingListItems) {
            $shoppingListSubtotal = 0;
            foreach ($shoppingListItems as $shoppingListItem) {
                if (empty($shoppingListItem[self::KEY_PRICE] || empty($shoppingListItem[static::KEY_QUANTITY]))) {
                    continue;
                }

                $shoppingListSubtotal += ($shoppingListItem[static::KEY_PRICE] * $shoppingListItem[static::KEY_QUANTITY]);
            }

            return $shoppingListSubtotal;
        });

        return $shoppingListSubtotalCalculatorMock;
    }
}
