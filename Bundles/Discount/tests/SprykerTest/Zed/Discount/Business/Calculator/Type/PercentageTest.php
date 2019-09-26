<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\Calculator\Type;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Discount\Business\Calculator\Type\PercentageType;
use Spryker\Zed\Discount\Business\Exception\CalculatorException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group Calculator
 * @group Type
 * @group PercentageTest
 * Add your own group annotations below this line
 */
class PercentageTest extends Unit
{
    public const ITEM_GROSS_PRICE_1000 = 1000;
    public const DISCOUNT_PERCENTAGE_10 = 1000;
    public const DISCOUNT_PERCENTAGE_100 = 10000;
    public const DISCOUNT_PERCENTAGE_200 = 20000;

    /**
     * @return void
     */
    public function testCalculatePercentageShouldNotGrantDiscountsHigherThanHundredPercent()
    {
        $items = $this->getDiscountableItems(
            [
                self::ITEM_GROSS_PRICE_1000,
                self::ITEM_GROSS_PRICE_1000,
                self::ITEM_GROSS_PRICE_1000,
            ]
        );

        $calculator = new PercentageType();
        $discountTransfer = (new DiscountTransfer())->setAmount(self::DISCOUNT_PERCENTAGE_200);
        $discountAmount = $calculator->calculateDiscount($items, $discountTransfer);

        $this->assertSame(self::ITEM_GROSS_PRICE_1000 * 3, $discountAmount);
    }

    /**
     * @return void
     */
    public function testCalculatePercentageShouldNotGrantDiscountsLessThanZeroPercent()
    {
        $items = $this->getDiscountableItems(
            [
                self::ITEM_GROSS_PRICE_1000,
                self::ITEM_GROSS_PRICE_1000,
                self::ITEM_GROSS_PRICE_1000,
            ]
        );

        $calculator = new PercentageType();
        $discountTransfer = (new DiscountTransfer())->setAmount(-1 * self::DISCOUNT_PERCENTAGE_200);
        $discountAmount = $calculator->calculateDiscount($items, $discountTransfer);

        $this->assertSame(0, $discountAmount);
    }

    /**
     * @return void
     */
    public function testCalculatePercentageShouldThrowAnExceptionForNonNumericValues()
    {
        $items = $this->getDiscountableItems(
            [
                self::ITEM_GROSS_PRICE_1000,
                self::ITEM_GROSS_PRICE_1000,
                self::ITEM_GROSS_PRICE_1000,
            ]
        );

        $calculator = new PercentageType();
        $this->expectException(CalculatorException::class);
        $discountCalculatorTransfer = (new DiscountTransfer())->setAmount('string');
        $calculator->calculateDiscount($items, $discountCalculatorTransfer);
    }

    /**
     * @return void
     */
    public function testCalculatePercentageShouldNotGiveNegativeDiscountAmounts()
    {
        $items = $this->getDiscountableItems(
            [
                -1 * self::ITEM_GROSS_PRICE_1000,
                -1 * self::ITEM_GROSS_PRICE_1000,
                -1 * self::ITEM_GROSS_PRICE_1000,
            ]
        );

        $calculator = new PercentageType();
        $discountCalculatorTransfer = (new DiscountTransfer())->setAmount(self::DISCOUNT_PERCENTAGE_10);
        $discountAmount = $calculator->calculateDiscount($items, $discountCalculatorTransfer);

        $this->assertSame(0, $discountAmount);
    }

    /**
     * @return void
     */
    public function testCalculatePercentageWhenQuantityIsNotSetShouldSetItToOne()
    {
        $items = $this->getDiscountableItems(
            [
                 self::ITEM_GROSS_PRICE_1000,
            ]
        );

        $items[0]->setQuantity(0);

        $calculator = new PercentageType();
        $discountTransfer = (new DiscountTransfer())->setAmount(self::DISCOUNT_PERCENTAGE_10);
        $discountAmount = $calculator->calculateDiscount($items, $discountTransfer);

        $this->assertNotEmpty($discountAmount);
    }

    /**
     * @param array $grossPrices
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    protected function getDiscountableItems(array $grossPrices)
    {
        $items = [];

        foreach ($grossPrices as $grossPrice) {
            $discountableItems = new DiscountableItemTransfer();
            $discountableItems->setUnitPrice($grossPrice);
            $discountableItems->setQuantity(1);
            $discountableItems->setOriginalItemCalculatedDiscounts(new ArrayObject());

            $items[] = $discountableItems;
        }

        return $items;
    }
}
