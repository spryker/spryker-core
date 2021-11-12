<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\Sorter;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CollectedDiscountTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Discount\Business\Sorter\CollectedDiscountSorter;
use Spryker\Zed\Discount\Business\Sorter\CollectedDiscountSorterInterface;
use Spryker\Zed\Discount\DiscountConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group Sorter
 * @group CollectedDiscountSorterTest
 * Add your own group annotations below this line
 */
class CollectedDiscountSorterTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_DISCOUNT_NAME_1 = 'discount 1';

    /**
     * @var string
     */
    protected const TEST_DISCOUNT_NAME_2 = 'discount 2';

    /**
     * @var string
     */
    protected const TEST_DISCOUNT_NAME_3 = 'discount 3';

    /**
     * @var \SprykerTest\Zed\Discount\DiscountBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider sortCollectedDiscountSorterByPriorityDataProvider
     *
     * @param array<array<string, mixed>> $discountData
     * @param array<string> $expectedDiscountNamesOrder
     *
     * @return void
     */
    public function testCollectedDiscountSorterShouldSortByPriorityCorrectly(array $discountData, array $expectedDiscountNamesOrder): void
    {
        // Arrange
        if (!$this->tester->createDiscountRepository()->hasPriorityField()) {
            $this->markTestSkipped('This test is not suitable for discounts without priority');
        }

        $this->executeCollectedDiscountSorterTest($discountData, $expectedDiscountNamesOrder);
    }

    /**
     * @dataProvider sortCollectedDiscountSorterByDiscountAmountDataProvider
     *
     * @param array<array<string, mixed>> $discountData
     * @param array<string> $expectedDiscountNamesOrder
     *
     * @return void
     */
    public function testCollectedDiscountSorterShouldSortByDiscountAmountWithoutPriority(array $discountData, array $expectedDiscountNamesOrder): void
    {
        $this->executeCollectedDiscountSorterTest($discountData, $expectedDiscountNamesOrder);
    }

    /**
     * @param array<array<string, mixed>> $discountData
     * @param array<string> $expectedDiscountNamesOrder
     *
     * @return void
     */
    protected function executeCollectedDiscountSorterTest(array $discountData, array $expectedDiscountNamesOrder): void
    {
        $collectedDiscounts = [];
        foreach ($discountData as $discountDatum) {
            $discountTransfer = (new DiscountTransfer())->fromArray($discountDatum, true);
            $collectedDiscounts[] = (new CollectedDiscountTransfer())->setDiscount($discountTransfer);
        }

        // Act
        $collectedDiscounts = $this->createCollectedDiscountSorter()->sort($collectedDiscounts);

        // Assert
        $discountNames = array_map(function (CollectedDiscountTransfer $collectedDiscountTransfer) {
            return $collectedDiscountTransfer->getDiscountOrFail()->getDisplayNameOrFail();
        }, $collectedDiscounts);
        $this->assertSame($expectedDiscountNamesOrder, $discountNames);
    }

    /**
     * @return array<string, array>
     */
    public function sortCollectedDiscountSorterByPriorityDataProvider(): array
    {
        return [
            'every discount has different priority' => [
                [
                    [DiscountTransfer::PRIORITY => 50, DiscountTransfer::AMOUNT => 100, DiscountTransfer::DISPLAY_NAME => static::TEST_DISCOUNT_NAME_1],
                    [DiscountTransfer::PRIORITY => 100, DiscountTransfer::AMOUNT => 100, DiscountTransfer::DISPLAY_NAME => static::TEST_DISCOUNT_NAME_2],
                    [DiscountTransfer::PRIORITY => 1, DiscountTransfer::AMOUNT => 100, DiscountTransfer::DISPLAY_NAME => static::TEST_DISCOUNT_NAME_3],
                ],
                [
                    static::TEST_DISCOUNT_NAME_3, static::TEST_DISCOUNT_NAME_1, static::TEST_DISCOUNT_NAME_2,
                ],
            ],
            'two discounts have same priority' => [
                [
                    [DiscountTransfer::PRIORITY => 50, DiscountTransfer::AMOUNT => 100, DiscountTransfer::DISPLAY_NAME => static::TEST_DISCOUNT_NAME_1],
                    [DiscountTransfer::PRIORITY => 1, DiscountTransfer::AMOUNT => 100, DiscountTransfer::DISPLAY_NAME => static::TEST_DISCOUNT_NAME_2],
                    [DiscountTransfer::PRIORITY => 50, DiscountTransfer::AMOUNT => 200, DiscountTransfer::DISPLAY_NAME => static::TEST_DISCOUNT_NAME_3],
                ],
                [
                    static::TEST_DISCOUNT_NAME_2, static::TEST_DISCOUNT_NAME_3, static::TEST_DISCOUNT_NAME_1,
                ],
            ],
            'all discounts have same priority' => [
                [
                    [DiscountTransfer::PRIORITY => 9999, DiscountTransfer::AMOUNT => 300, DiscountTransfer::DISPLAY_NAME => static::TEST_DISCOUNT_NAME_1],
                    [DiscountTransfer::PRIORITY => 9999, DiscountTransfer::AMOUNT => 100, DiscountTransfer::DISPLAY_NAME => static::TEST_DISCOUNT_NAME_2],
                    [DiscountTransfer::PRIORITY => 9999, DiscountTransfer::AMOUNT => 200, DiscountTransfer::DISPLAY_NAME => static::TEST_DISCOUNT_NAME_3],
                ],
                [
                    static::TEST_DISCOUNT_NAME_1, static::TEST_DISCOUNT_NAME_3, static::TEST_DISCOUNT_NAME_2,
                ],
            ],
        ];
    }

    /**
     * @return array<string, array>
     */
    public function sortCollectedDiscountSorterByDiscountAmountDataProvider(): array
    {
        return [
            'discounts with the same amount' => [
                [
                    [DiscountTransfer::AMOUNT => 100, DiscountTransfer::DISPLAY_NAME => static::TEST_DISCOUNT_NAME_1],
                    [DiscountTransfer::AMOUNT => 100, DiscountTransfer::DISPLAY_NAME => static::TEST_DISCOUNT_NAME_2],
                    [DiscountTransfer::AMOUNT => 100, DiscountTransfer::DISPLAY_NAME => static::TEST_DISCOUNT_NAME_3],
                ],
                [
                    static::TEST_DISCOUNT_NAME_1, static::TEST_DISCOUNT_NAME_2, static::TEST_DISCOUNT_NAME_3,
                ],
            ],
            'two discounts have the same amount' => [
                [
                    [DiscountTransfer::AMOUNT => 100, DiscountTransfer::DISPLAY_NAME => static::TEST_DISCOUNT_NAME_1],
                    [DiscountTransfer::AMOUNT => 100, DiscountTransfer::DISPLAY_NAME => static::TEST_DISCOUNT_NAME_2],
                    [DiscountTransfer::AMOUNT => 200, DiscountTransfer::DISPLAY_NAME => static::TEST_DISCOUNT_NAME_3],
                ],
                [
                    static::TEST_DISCOUNT_NAME_3, static::TEST_DISCOUNT_NAME_1, static::TEST_DISCOUNT_NAME_2,
                ],
            ],
            'all discounts have different amounts' => [
                [
                    [DiscountTransfer::AMOUNT => 300, DiscountTransfer::DISPLAY_NAME => static::TEST_DISCOUNT_NAME_1],
                    [DiscountTransfer::AMOUNT => 100, DiscountTransfer::DISPLAY_NAME => static::TEST_DISCOUNT_NAME_2],
                    [DiscountTransfer::AMOUNT => 200, DiscountTransfer::DISPLAY_NAME => static::TEST_DISCOUNT_NAME_3],
                ],
                [
                    static::TEST_DISCOUNT_NAME_1, static::TEST_DISCOUNT_NAME_3, static::TEST_DISCOUNT_NAME_2,
                ],
            ],
        ];
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Sorter\CollectedDiscountSorterInterface
     */
    protected function createCollectedDiscountSorter(): CollectedDiscountSorterInterface
    {
        return new CollectedDiscountSorter(
            $this->tester->createDiscountRepository(),
            $this->createDiscountConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Discount\DiscountConfig
     */
    protected function createDiscountConfig(): DiscountConfig
    {
        return new DiscountConfig();
    }
}
