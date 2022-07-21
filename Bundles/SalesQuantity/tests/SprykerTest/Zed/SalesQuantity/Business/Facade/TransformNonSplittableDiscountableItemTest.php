<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesQuantity\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountableItemTransformerTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesQuantity
 * @group Business
 * @group Facade
 * @group TransformNonSplittableDiscountableItemTest
 * Add your own group annotations below this line
 */
class TransformNonSplittableDiscountableItemTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Discount\DiscountDependencyProvider::PLUGIN_CALCULATOR_FIXED
     *
     * @var string
     */
    protected const PLUGIN_CALCULATOR_FIXED = 'PLUGIN_CALCULATOR_FIXED';

    /**
     * @uses \Spryker\Zed\Discount\DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE
     *
     * @var string
     */
    protected const PLUGIN_CALCULATOR_PERCENTAGE = 'PLUGIN_CALCULATOR_PERCENTAGE';

    /**
     * @var \SprykerTest\Zed\SalesQuantity\SalesQuantityBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider transformNonSplittableDiscountableItemWithPriorityDataProvider
     *
     * @param array<string, mixed> $discountData
     * @param array<string, mixed> $calculatedItemData
     * @param array<string, mixed> $discountableItemTransformerData
     * @param int $calculatedDiscountOffset
     * @param int $calculatedDiscountExpectedAmount
     *
     * @return void
     */
    public function testTransformNonSplittableDiscountableItemShouldCalculateCorrectAmountForDiscountsWithPriority(
        array $discountData,
        array $calculatedItemData,
        array $discountableItemTransformerData,
        int $calculatedDiscountOffset,
        int $calculatedDiscountExpectedAmount
    ): void {
        // Arrange
        if (!$this->tester->discountPriorityFieldExists()) {
            $this->markTestSkipped('This test is not suitable for discounts without priority');
        }

        $discountTransfer = (new DiscountTransfer())->fromArray($discountData, true);
        $discountableItemTransfer = (new DiscountableItemTransfer())->fromArray($calculatedItemData, true);
        $discountableItemTransformerTransfer = (new DiscountableItemTransformerTransfer())->fromArray($discountableItemTransformerData, true)
            ->setDiscount($discountTransfer)
            ->setDiscountableItem($discountableItemTransfer);

        // Act
        $discountableItemTransformerTransfer = $this->tester->getFacade()
            ->transformNonSplittableDiscountableItem($discountableItemTransformerTransfer);

        // Assert
        $resultDiscountableItemTransfer = $discountableItemTransformerTransfer->getDiscountableItemOrFail();
        $this->assertTrue($resultDiscountableItemTransfer->getOriginalItemCalculatedDiscounts()->offsetExists($calculatedDiscountOffset));

        /** @var \Generated\Shared\Transfer\CalculatedDiscountTransfer $calculatedDiscountTransfer */
        $calculatedDiscountTransfer = $resultDiscountableItemTransfer->getOriginalItemCalculatedDiscounts()->offsetGet($calculatedDiscountOffset);
        $this->assertSame($calculatedDiscountExpectedAmount, $calculatedDiscountTransfer->getSumAmount());
    }

    /**
     * @return array<string, array>
     */
    public function transformNonSplittableDiscountableItemWithPriorityDataProvider(): array
    {
        return [
            'no calculated discounts (percentage)' => [
                [DiscountTransfer::PRIORITY => 10, DiscountTransfer::CALCULATOR_PLUGIN => static::PLUGIN_CALCULATOR_PERCENTAGE],
                [DiscountableItemTransfer::UNIT_PRICE => 1000, DiscountableItemTransfer::ORIGINAL_ITEM => new ItemTransfer()],
                [
                    DiscountableItemTransformerTransfer::QUANTITY => 4,
                    DiscountableItemTransformerTransfer::ROUNDING_ERROR => 0.0,
                    DiscountableItemTransformerTransfer::TOTAL_AMOUNT => 1000,
                    DiscountableItemTransformerTransfer::TOTAL_DISCOUNT_AMOUNT => 100,
                ],
                0,
                400,
            ],
            'no calculated discounts (fixed)' => [
                [DiscountTransfer::PRIORITY => 10, DiscountTransfer::CALCULATOR_PLUGIN => static::PLUGIN_CALCULATOR_FIXED],
                [DiscountableItemTransfer::UNIT_PRICE => 1000, DiscountableItemTransfer::ORIGINAL_ITEM => new ItemTransfer()],
                [
                    DiscountableItemTransformerTransfer::QUANTITY => 4,
                    DiscountableItemTransformerTransfer::ROUNDING_ERROR => 0.0,
                    DiscountableItemTransformerTransfer::TOTAL_AMOUNT => 1000,
                    DiscountableItemTransformerTransfer::TOTAL_DISCOUNT_AMOUNT => 100,
                ],
                0,
                400,
            ],
            'one calculated discount (percentage)' => [
                [DiscountTransfer::PRIORITY => 10, DiscountTransfer::CALCULATOR_PLUGIN => static::PLUGIN_CALCULATOR_PERCENTAGE],
                [
                    DiscountableItemTransfer::UNIT_PRICE => 1000,
                    DiscountableItemTransfer::ORIGINAL_ITEM => new ItemTransfer(),
                    DiscountableItemTransfer::ORIGINAL_ITEM_CALCULATED_DISCOUNTS => [
                        [
                            CalculatedDiscountTransfer::PRIORITY => 9,
                            CalculatedDiscountTransfer::UNIT_AMOUNT => 100,
                        ],
                    ],
                ],
                [
                    DiscountableItemTransformerTransfer::QUANTITY => 4,
                    DiscountableItemTransformerTransfer::ROUNDING_ERROR => 0.0,
                    DiscountableItemTransformerTransfer::TOTAL_AMOUNT => 1000,
                    DiscountableItemTransformerTransfer::TOTAL_DISCOUNT_AMOUNT => 100,
                ],
                1,
                360,
            ],
            'one calculated discount (fixed)' => [
                [DiscountTransfer::PRIORITY => 10, DiscountTransfer::CALCULATOR_PLUGIN => static::PLUGIN_CALCULATOR_FIXED],
                [
                    DiscountableItemTransfer::UNIT_PRICE => 1000,
                    DiscountableItemTransfer::ORIGINAL_ITEM => new ItemTransfer(),
                    DiscountableItemTransfer::ORIGINAL_ITEM_CALCULATED_DISCOUNTS => [
                        [
                            CalculatedDiscountTransfer::PRIORITY => 9,
                            CalculatedDiscountTransfer::UNIT_AMOUNT => 100,
                        ],
                    ],
                ],
                [
                    DiscountableItemTransformerTransfer::QUANTITY => 4,
                    DiscountableItemTransformerTransfer::ROUNDING_ERROR => 0.0,
                    DiscountableItemTransformerTransfer::TOTAL_AMOUNT => 1000,
                    DiscountableItemTransformerTransfer::TOTAL_DISCOUNT_AMOUNT => 100,
                ],
                1,
                400,
            ],
            'two calculated discounts (percentage)' => [
                [DiscountTransfer::PRIORITY => 10, DiscountTransfer::CALCULATOR_PLUGIN => static::PLUGIN_CALCULATOR_PERCENTAGE],
                [
                    DiscountableItemTransfer::UNIT_PRICE => 1000,
                    DiscountableItemTransfer::ORIGINAL_ITEM => new ItemTransfer(),
                    DiscountableItemTransfer::ORIGINAL_ITEM_CALCULATED_DISCOUNTS => [
                        [
                            CalculatedDiscountTransfer::PRIORITY => 8,
                            CalculatedDiscountTransfer::UNIT_AMOUNT => 100,
                        ],
                        [
                            CalculatedDiscountTransfer::PRIORITY => 9,
                            CalculatedDiscountTransfer::UNIT_AMOUNT => 200,
                        ],
                    ],
                ],
                [
                    DiscountableItemTransformerTransfer::QUANTITY => 4,
                    DiscountableItemTransformerTransfer::ROUNDING_ERROR => 0.0,
                    DiscountableItemTransformerTransfer::TOTAL_AMOUNT => 1000,
                    DiscountableItemTransformerTransfer::TOTAL_DISCOUNT_AMOUNT => 100,
                ],
                2,
                280,
            ],
            'two calculated discounts (fixed)' => [
                [DiscountTransfer::PRIORITY => 10, DiscountTransfer::CALCULATOR_PLUGIN => static::PLUGIN_CALCULATOR_FIXED],
                [
                    DiscountableItemTransfer::UNIT_PRICE => 1000,
                    DiscountableItemTransfer::ORIGINAL_ITEM => new ItemTransfer(),
                    DiscountableItemTransfer::ORIGINAL_ITEM_CALCULATED_DISCOUNTS => [
                        [
                            CalculatedDiscountTransfer::PRIORITY => 8,
                            CalculatedDiscountTransfer::UNIT_AMOUNT => 100,
                        ],
                        [
                            CalculatedDiscountTransfer::PRIORITY => 9,
                            CalculatedDiscountTransfer::UNIT_AMOUNT => 200,
                        ],
                    ],
                ],
                [
                    DiscountableItemTransformerTransfer::QUANTITY => 4,
                    DiscountableItemTransformerTransfer::ROUNDING_ERROR => 0.0,
                    DiscountableItemTransformerTransfer::TOTAL_AMOUNT => 1000,
                    DiscountableItemTransformerTransfer::TOTAL_DISCOUNT_AMOUNT => 100,
                ],
                2,
                400,
            ],
            'two calculated discounts (percentage) + same priority, bigger amount' => [
                [DiscountTransfer::PRIORITY => 10, DiscountTransfer::CALCULATOR_PLUGIN => static::PLUGIN_CALCULATOR_PERCENTAGE, DiscountTransfer::AMOUNT => 500],
                [
                    DiscountableItemTransfer::UNIT_PRICE => 1000,
                    DiscountableItemTransfer::ORIGINAL_ITEM => new ItemTransfer(),
                    DiscountableItemTransfer::ORIGINAL_ITEM_CALCULATED_DISCOUNTS => [
                        [
                            CalculatedDiscountTransfer::PRIORITY => 8,
                            CalculatedDiscountTransfer::UNIT_AMOUNT => 100,
                        ],
                        [
                            CalculatedDiscountTransfer::PRIORITY => 10,
                            CalculatedDiscountTransfer::UNIT_AMOUNT => 200,
                        ],
                    ],
                ],
                [
                    DiscountableItemTransformerTransfer::QUANTITY => 4,
                    DiscountableItemTransformerTransfer::ROUNDING_ERROR => 0.0,
                    DiscountableItemTransformerTransfer::TOTAL_AMOUNT => 1000,
                    DiscountableItemTransformerTransfer::TOTAL_DISCOUNT_AMOUNT => 500,
                ],
                2,
                1800,
            ],
            'two calculated discounts (fixed) + same priority, bigger amount' => [
                [DiscountTransfer::PRIORITY => 10, DiscountTransfer::CALCULATOR_PLUGIN => static::PLUGIN_CALCULATOR_FIXED, DiscountTransfer::AMOUNT => 500],
                [
                    DiscountableItemTransfer::UNIT_PRICE => 1000,
                    DiscountableItemTransfer::ORIGINAL_ITEM => new ItemTransfer(),
                    DiscountableItemTransfer::ORIGINAL_ITEM_CALCULATED_DISCOUNTS => [
                        [
                            CalculatedDiscountTransfer::PRIORITY => 8,
                            CalculatedDiscountTransfer::UNIT_AMOUNT => 100,
                        ],
                        [
                            CalculatedDiscountTransfer::PRIORITY => 10,
                            CalculatedDiscountTransfer::UNIT_AMOUNT => 200,
                        ],
                    ],
                ],
                [
                    DiscountableItemTransformerTransfer::QUANTITY => 4,
                    DiscountableItemTransformerTransfer::ROUNDING_ERROR => 0.0,
                    DiscountableItemTransformerTransfer::TOTAL_AMOUNT => 1000,
                    DiscountableItemTransformerTransfer::TOTAL_DISCOUNT_AMOUNT => 500,
                ],
                2,
                2000,
            ],
            'two calculated discounts (percentage) + same priority, smaller amount' => [
                [DiscountTransfer::PRIORITY => 10, DiscountTransfer::CALCULATOR_PLUGIN => static::PLUGIN_CALCULATOR_PERCENTAGE, DiscountTransfer::AMOUNT => 100],
                [
                    DiscountableItemTransfer::UNIT_PRICE => 1000,
                    DiscountableItemTransfer::ORIGINAL_ITEM => new ItemTransfer(),
                    DiscountableItemTransfer::ORIGINAL_ITEM_CALCULATED_DISCOUNTS => [
                        [
                            CalculatedDiscountTransfer::PRIORITY => 8,
                            CalculatedDiscountTransfer::UNIT_AMOUNT => 100,
                        ],
                        [
                            CalculatedDiscountTransfer::PRIORITY => 10,
                            CalculatedDiscountTransfer::UNIT_AMOUNT => 200,
                        ],
                    ],
                ],
                [
                    DiscountableItemTransformerTransfer::QUANTITY => 4,
                    DiscountableItemTransformerTransfer::ROUNDING_ERROR => 0.0,
                    DiscountableItemTransformerTransfer::TOTAL_AMOUNT => 1000,
                    DiscountableItemTransformerTransfer::TOTAL_DISCOUNT_AMOUNT => 100,
                ],
                2,
                360,
            ],
            'two calculated discounts (fixed) + same priority, smaller amount' => [
                [DiscountTransfer::PRIORITY => 10, DiscountTransfer::CALCULATOR_PLUGIN => static::PLUGIN_CALCULATOR_FIXED, DiscountTransfer::AMOUNT => 100],
                [
                    DiscountableItemTransfer::UNIT_PRICE => 1000,
                    DiscountableItemTransfer::ORIGINAL_ITEM => new ItemTransfer(),
                    DiscountableItemTransfer::ORIGINAL_ITEM_CALCULATED_DISCOUNTS => [
                        [
                            CalculatedDiscountTransfer::PRIORITY => 8,
                            CalculatedDiscountTransfer::UNIT_AMOUNT => 100,
                        ],
                        [
                            CalculatedDiscountTransfer::PRIORITY => 10,
                            CalculatedDiscountTransfer::UNIT_AMOUNT => 200,
                        ],
                    ],
                ],
                [
                    DiscountableItemTransformerTransfer::QUANTITY => 4,
                    DiscountableItemTransformerTransfer::ROUNDING_ERROR => 0.0,
                    DiscountableItemTransformerTransfer::TOTAL_AMOUNT => 1000,
                    DiscountableItemTransformerTransfer::TOTAL_DISCOUNT_AMOUNT => 100,
                ],
                2,
                400,
            ],
        ];
    }
}
