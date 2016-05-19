<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Business\Calculator;

use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Discount\Business\Calculator\Percentage;

/**
 * Class PercentageTest
 *
 * @group DiscountCalculatorPercentageTest
 * @group Discount
 */
class PercentageTest extends \PHPUnit_Framework_TestCase
{

    const ITEM_GROSS_PRICE_1000 = 1000;
    const DISCOUNT_PERCENTAGE_10 = 10;
    const DISCOUNT_PERCENTAGE_100 = 100;
    const DISCOUNT_PERCENTAGE_200 = 200;

    /**
     * @return void
     */
    public function testCalculatePercentageShouldNotGrantDiscountsHigherThanHundredPercent()
    {
        $items = $this->getItems(
            [
                self::ITEM_GROSS_PRICE_1000,
                self::ITEM_GROSS_PRICE_1000,
                self::ITEM_GROSS_PRICE_1000,
            ]
        );

        $calculator = new Percentage();
        $discountAmount = $calculator->calculate($items, self::DISCOUNT_PERCENTAGE_200);

        $this->assertEquals(self::ITEM_GROSS_PRICE_1000 * 3, $discountAmount);
    }

    /**
     * @return void
     */
    public function testCalculatePercentageShouldNotGrantDiscountsLessThanZeroPercent()
    {
        $items = $this->getItems(
            [
                self::ITEM_GROSS_PRICE_1000,
                self::ITEM_GROSS_PRICE_1000,
                self::ITEM_GROSS_PRICE_1000,
            ]
        );

        $calculator = new Percentage();
        $discountAmount = $calculator->calculate($items, -1 * self::DISCOUNT_PERCENTAGE_200);

        $this->assertEquals(0, $discountAmount);
    }

    /**
     * @return void
     */
    public function testCalculatePercentageShouldThrowAnExceptionForNonNumericValues()
    {
        $items = $this->getItems(
            [
                self::ITEM_GROSS_PRICE_1000,
                self::ITEM_GROSS_PRICE_1000,
                self::ITEM_GROSS_PRICE_1000,
            ]
        );

        $calculator = new Percentage();
        $this->setExpectedException('InvalidArgumentException');
        $discountAmount = $calculator->calculate($items, 'string');
    }

    /**
     * @return void
     */
    public function testCalculatePercentageShouldNotGiveNegativeDiscountAmounts()
    {
        $items = $this->getItems(
            [
                -1 * self::ITEM_GROSS_PRICE_1000,
                -1 * self::ITEM_GROSS_PRICE_1000,
                -1 * self::ITEM_GROSS_PRICE_1000,
            ]
        );

        $calculator = new Percentage();
        $discountAmount = $calculator->calculate($items, self::DISCOUNT_PERCENTAGE_10);

        $this->assertEquals(0, $discountAmount);
    }

    /**
     * @param array $grossPrices
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    protected function getItems(array $grossPrices)
    {
        $items = [];

        foreach ($grossPrices as $grossPrice) {
            $item = new ItemTransfer();
            $item->setUnitGrossPrice($grossPrice);
            $item->setQuantity(1);

            $discountableItems = new DiscountableItemTransfer();
            $discountableItems->setUnitGrossPrice($grossPrice);
            $discountableItems->setQuantity(1);
            $discountableItems->setOriginalItemCalculatedDiscounts(new \ArrayObject());

            $items[] = $discountableItems;
        }

        return $items;
    }

}
