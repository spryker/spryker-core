<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Discount\DiscountService;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountCalculationRequestTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Spryker\Service\Discount\Exception\DiscountCalculatorPluginNotFoundException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group Discount
 * @group DiscountService
 * @group CalculateTest
 * Add your own group annotations below this line
 */
class CalculateTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_CALCULATOR_PLUGIN_FAKE_NAME = 'CALCULATOR_PLUGIN_FAKE_NAME';

    /**
     * @var string
     */
    protected const TEST_PLUGIN_CALCULATOR_PERCENTAGE = 'PLUGIN_CALCULATOR_PERCENTAGE';

    /**
     * @var string
     */
    protected const TEST_PLUGIN_CALCULATOR_FIXED = 'PLUGIN_CALCULATOR_FIXED';

    /**
     * @var string
     */
    protected const TEST_PRICE_NET_MODE = 'NET_MODE';

    /**
     * @var string
     */
    protected const TEST_CURRENCY_1 = 'TEST_CURRENCY_1';

    /**
     * @var int
     */
    protected const TEST_DISCOUNT_AMOUNT = 7000;

    /**
     * @var int
     */
    protected const TEST_DISCOUNT_UNIT_PRICE = 3000;

    /**
     * @var int
     */
    protected const TEST_DISCOUNT_ITEM_QUANTITY = 2;

    /**
     * @var \SprykerTest\Service\Discount\DiscountServiceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testReturnsExpectedAmountWhenUseCalculatorStrategyPercentagePlugin(): void
    {
        // Arrange
        $currency = (new CurrencyTransfer())->setCode(static::TEST_CURRENCY_1);
        $discountableItemTransfer = (new DiscountableItemTransfer())
            ->setUnitPrice(static::TEST_DISCOUNT_UNIT_PRICE)
            ->setQuantity(static::TEST_DISCOUNT_ITEM_QUANTITY);

        $discountCalculationRequestTransfer = (new DiscountCalculationRequestTransfer())
            ->setDiscountableItems(new ArrayObject([$discountableItemTransfer]))
            ->setDiscount(
                (new DiscountTransfer())
                    ->setAmount(static::TEST_DISCOUNT_AMOUNT)
                    ->setCalculatorPlugin(static::TEST_PLUGIN_CALCULATOR_PERCENTAGE)
                    ->setCurrency($currency),
            );

        // Act
        $discountCalculationResponseTransfer = $this->tester->getDiscountService()->calculate($discountCalculationRequestTransfer);

        // Assert
        $this->assertSame(4200, $discountCalculationResponseTransfer->getAmount());
    }

    /**
     * @return void
     */
    public function testReturnsExpectedAmountWhenUseCalculatorStrategyFixedPlugin(): void
    {
        // Arrange
        $currency = (new CurrencyTransfer())->setCode(static::TEST_CURRENCY_1);
        $moneyTransfer = (new MoneyValueTransfer())->setNetAmount(static::TEST_DISCOUNT_AMOUNT)->setCurrency($currency);

        $discountCalculationRequestTransfer = (new DiscountCalculationRequestTransfer())
            ->setDiscountableItems(new ArrayObject([]))
            ->setDiscount(
                (new DiscountTransfer())
                    ->setCalculatorPlugin(static::TEST_PLUGIN_CALCULATOR_FIXED)
                    ->setCurrency($currency)
                    ->setPriceMode(static::TEST_PRICE_NET_MODE)
                    ->setMoneyValueCollection(new ArrayObject([$moneyTransfer])),
            );

        // Act
        $discountCalculationResponseTransfer = $this->tester->getDiscountService()->calculate($discountCalculationRequestTransfer);

        // Assert
        $this->assertSame(static::TEST_DISCOUNT_AMOUNT, $discountCalculationResponseTransfer->getAmount());
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenCalculatorStrategyPluginNotFound(): void
    {
        // Arrange
        $discountCalculationRequestTransfer = (new DiscountCalculationRequestTransfer())
            ->setDiscount(
                (new DiscountTransfer())->setCalculatorPlugin(static::TEST_CALCULATOR_PLUGIN_FAKE_NAME),
            );

        // Assert
        $this->expectException(DiscountCalculatorPluginNotFoundException::class);

        // Act
        $this->tester->getDiscountService()->calculate($discountCalculationRequestTransfer);
    }
}
