<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\Distributor\DiscountableItem;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountableItemTransformerTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Discount\Business\Distributor\DiscountableItem\DiscountableItemTransformer;
use Spryker\Zed\Discount\Business\Distributor\DiscountableItem\DiscountableItemTransformerInterface;
use Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group Distributor
 * @group DiscountableItem
 * @group DiscountableItemTransformerTest
 * Add your own group annotations below this line
 */
class DiscountableItemTransformerTest extends Unit
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
     * @var \SprykerTest\Zed\Discount\DiscountBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider transformSplittableDiscountableItemWithPriorityDataProvider
     *
     * @param array<string, mixed> $discountData
     * @param array<string, mixed> $calculatedItemData
     * @param array<string, mixed> $discountableItemTransformerData
     * @param int $calculatedDiscountOffset
     * @param int $calculatedDiscountExpectedAmount
     *
     * @return void
     */
    public function testTransformSplittableDiscountableItemShouldCalculateCorrectAmountForDiscountsWithPriority(
        array $discountData,
        array $calculatedItemData,
        array $discountableItemTransformerData,
        int $calculatedDiscountOffset,
        int $calculatedDiscountExpectedAmount
    ): void {
        // Arrange
        $discountRepository = $this->tester->createDiscountRepository();
        if (!$discountRepository->hasPriorityField()) {
            $this->markTestSkipped('This test is not suitable for discounts without priority');
        }

        $discountTransfer = (new DiscountTransfer())->fromArray($discountData, true);
        $discountableItemTransfer = (new DiscountableItemTransfer())->fromArray($calculatedItemData, true);
        $discountableItemTransformerTransfer = (new DiscountableItemTransformerTransfer())->fromArray($discountableItemTransformerData, true)
            ->setDiscount($discountTransfer)
            ->setDiscountableItem($discountableItemTransfer);

        // Act
        $discountableItemTransformerTransfer = $this->createDiscountableItemTransformer($discountRepository)
            ->transformSplittableDiscountableItem($discountableItemTransformerTransfer);

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
    public function transformSplittableDiscountableItemWithPriorityDataProvider(): array
    {
        return [
            'no calculated discounts (percentage)' => [
                [DiscountTransfer::PRIORITY => 10, DiscountTransfer::CALCULATOR_PLUGIN => static::PLUGIN_CALCULATOR_PERCENTAGE],
                [DiscountableItemTransfer::UNIT_PRICE => 1000, DiscountableItemTransfer::ORIGINAL_ITEM => new ItemTransfer()],
                [
                    DiscountableItemTransformerTransfer::QUANTITY => 1,
                    DiscountableItemTransformerTransfer::ROUNDING_ERROR => 0.0,
                    DiscountableItemTransformerTransfer::TOTAL_AMOUNT => 1000,
                    DiscountableItemTransformerTransfer::TOTAL_DISCOUNT_AMOUNT => 100,
                ],
                0,
                100,
            ],
            'no calculated discounts (fixed)' => [
                [DiscountTransfer::PRIORITY => 10, DiscountTransfer::CALCULATOR_PLUGIN => static::PLUGIN_CALCULATOR_FIXED],
                [DiscountableItemTransfer::UNIT_PRICE => 1000, DiscountableItemTransfer::ORIGINAL_ITEM => new ItemTransfer()],
                [
                    DiscountableItemTransformerTransfer::QUANTITY => 1,
                    DiscountableItemTransformerTransfer::ROUNDING_ERROR => 0.0,
                    DiscountableItemTransformerTransfer::TOTAL_AMOUNT => 1000,
                    DiscountableItemTransformerTransfer::TOTAL_DISCOUNT_AMOUNT => 100,
                ],
                0,
                100,
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
                            CalculatedDiscountTransfer::ID_DISCOUNT => 1,
                        ],
                    ],
                ],
                [
                    DiscountableItemTransformerTransfer::QUANTITY => 1,
                    DiscountableItemTransformerTransfer::ROUNDING_ERROR => 0.0,
                    DiscountableItemTransformerTransfer::TOTAL_AMOUNT => 1000,
                    DiscountableItemTransformerTransfer::TOTAL_DISCOUNT_AMOUNT => 100,
                ],
                1,
                90,
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
                            CalculatedDiscountTransfer::ID_DISCOUNT => 1,
                        ],
                    ],
                ],
                [
                    DiscountableItemTransformerTransfer::QUANTITY => 1,
                    DiscountableItemTransformerTransfer::ROUNDING_ERROR => 0.0,
                    DiscountableItemTransformerTransfer::TOTAL_AMOUNT => 1000,
                    DiscountableItemTransformerTransfer::TOTAL_DISCOUNT_AMOUNT => 100,
                ],
                1,
                100,
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
                            CalculatedDiscountTransfer::ID_DISCOUNT => 1,
                        ],
                        [
                            CalculatedDiscountTransfer::PRIORITY => 9,
                            CalculatedDiscountTransfer::UNIT_AMOUNT => 200,
                            CalculatedDiscountTransfer::ID_DISCOUNT => 2,
                        ],
                    ],
                ],
                [
                    DiscountableItemTransformerTransfer::QUANTITY => 1,
                    DiscountableItemTransformerTransfer::ROUNDING_ERROR => 0.0,
                    DiscountableItemTransformerTransfer::TOTAL_AMOUNT => 1000,
                    DiscountableItemTransformerTransfer::TOTAL_DISCOUNT_AMOUNT => 100,
                ],
                2,
                70,
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
                            CalculatedDiscountTransfer::ID_DISCOUNT => 1,
                        ],
                        [
                            CalculatedDiscountTransfer::PRIORITY => 9,
                            CalculatedDiscountTransfer::UNIT_AMOUNT => 200,
                            CalculatedDiscountTransfer::ID_DISCOUNT => 2,
                        ],
                    ],
                ],
                [
                    DiscountableItemTransformerTransfer::QUANTITY => 1,
                    DiscountableItemTransformerTransfer::ROUNDING_ERROR => 0.0,
                    DiscountableItemTransformerTransfer::TOTAL_AMOUNT => 1000,
                    DiscountableItemTransformerTransfer::TOTAL_DISCOUNT_AMOUNT => 100,
                ],
                2,
                100,
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
                            CalculatedDiscountTransfer::ID_DISCOUNT => 1,
                        ],
                        [
                            CalculatedDiscountTransfer::PRIORITY => 10,
                            CalculatedDiscountTransfer::UNIT_AMOUNT => 200,
                            CalculatedDiscountTransfer::ID_DISCOUNT => 2,
                        ],
                    ],
                ],
                [
                    DiscountableItemTransformerTransfer::QUANTITY => 1,
                    DiscountableItemTransformerTransfer::ROUNDING_ERROR => 0.0,
                    DiscountableItemTransformerTransfer::TOTAL_AMOUNT => 1000,
                    DiscountableItemTransformerTransfer::TOTAL_DISCOUNT_AMOUNT => 500,
                ],
                2,
                450,
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
                            CalculatedDiscountTransfer::ID_DISCOUNT => 1,
                        ],
                        [
                            CalculatedDiscountTransfer::PRIORITY => 10,
                            CalculatedDiscountTransfer::UNIT_AMOUNT => 200,
                            CalculatedDiscountTransfer::ID_DISCOUNT => 2,
                        ],
                    ],
                ],
                [
                    DiscountableItemTransformerTransfer::QUANTITY => 1,
                    DiscountableItemTransformerTransfer::ROUNDING_ERROR => 0.0,
                    DiscountableItemTransformerTransfer::TOTAL_AMOUNT => 1000,
                    DiscountableItemTransformerTransfer::TOTAL_DISCOUNT_AMOUNT => 500,
                ],
                2,
                500,
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
                            CalculatedDiscountTransfer::ID_DISCOUNT => 1,
                        ],
                        [
                            CalculatedDiscountTransfer::PRIORITY => 10,
                            CalculatedDiscountTransfer::UNIT_AMOUNT => 200,
                            CalculatedDiscountTransfer::ID_DISCOUNT => 2,
                        ],
                    ],
                ],
                [
                    DiscountableItemTransformerTransfer::QUANTITY => 1,
                    DiscountableItemTransformerTransfer::ROUNDING_ERROR => 0.0,
                    DiscountableItemTransformerTransfer::TOTAL_AMOUNT => 1000,
                    DiscountableItemTransformerTransfer::TOTAL_DISCOUNT_AMOUNT => 100,
                ],
                2,
                90,
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
                            CalculatedDiscountTransfer::ID_DISCOUNT => 1,
                        ],
                        [
                            CalculatedDiscountTransfer::PRIORITY => 10,
                            CalculatedDiscountTransfer::UNIT_AMOUNT => 200,
                            CalculatedDiscountTransfer::ID_DISCOUNT => 2,
                        ],
                    ],
                ],
                [
                    DiscountableItemTransformerTransfer::QUANTITY => 1,
                    DiscountableItemTransformerTransfer::ROUNDING_ERROR => 0.0,
                    DiscountableItemTransformerTransfer::TOTAL_AMOUNT => 1000,
                    DiscountableItemTransformerTransfer::TOTAL_DISCOUNT_AMOUNT => 100,
                ],
                2,
                100,
            ],
        ];
    }

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface $discountRepository
     *
     * @return \Spryker\Zed\Discount\Business\Distributor\DiscountableItem\DiscountableItemTransformerInterface
     */
    protected function createDiscountableItemTransformer(DiscountRepositoryInterface $discountRepository): DiscountableItemTransformerInterface
    {
        return new DiscountableItemTransformer($discountRepository);
    }
}
