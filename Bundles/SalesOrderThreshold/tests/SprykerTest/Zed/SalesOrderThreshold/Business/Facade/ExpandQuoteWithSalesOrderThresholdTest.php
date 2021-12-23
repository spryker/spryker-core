<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderThreshold\Business\Facade;

use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\SalesOrderThresholdValueBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig;
use Spryker\Zed\SalesOrderThreshold\SalesOrderThresholdDependencyProvider;
use Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin\SalesOrderThresholdDataSourceStrategyPluginInterface;
use SprykerTest\Zed\SalesOrderThreshold\Business\SalesOrderThresholdMocks;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderThreshold
 * @group Business
 * @group Facade
 * @group ExpandQuoteWithSalesOrderThresholdTest
 * Add your own group annotations below this line
 */
class ExpandQuoteWithSalesOrderThresholdTest extends SalesOrderThresholdMocks
{
    /**
     * @uses \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_STRATEGY_KEY_HARD_MAXIMUM
     *
     * @var string
     */
    protected const THRESHOLD_STRATEGY_KEY_HARD_MAXIMUM = 'hard-maximum-threshold';

    /**
     * @var \SprykerTest\Zed\SalesOrderThreshold\SalesOrderThresholdBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider quoteExpandedWithValidThresholdDeltasDataProvider
     *
     * @param int $minimumThresholdValue
     * @param int $maximumThresholdValue
     * @param int $quoteItemQuantity
     * @param int $quoteItemPrice
     * @param int $expectedTotals
     * @param int $expectedMinimumDelta
     * @param int $expectedMaximumDelta
     *
     * @return void
     */
    public function testExpandQuoteWithSalesOrderThresholdValues(
        int $minimumThresholdValue,
        int $maximumThresholdValue,
        int $quoteItemQuantity,
        int $quoteItemPrice,
        int $expectedTotals,
        int $expectedMinimumDelta,
        int $expectedMaximumDelta
    ): void {
        // Arrange
        $this->tester->setupThresholdDependencies($minimumThresholdValue, $maximumThresholdValue);

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::QUANTITY => $quoteItemQuantity,
            ItemTransfer::UNIT_NET_PRICE => $quoteItemPrice,
        ]))->build();
        $totalsTransfer = (new TotalsTransfer())->setSubtotal($expectedTotals);
        $quoteTransfer = $this->tester->createTestQuoteTransfer()->addItem($itemTransfer)->setTotals($totalsTransfer);

        // Act
        $quoteTransfer = $this->tester->getFacade()->expandQuoteWithSalesOrderThresholdValues($quoteTransfer);

        // Assert
        $this->assertSame($expectedTotals, $quoteTransfer->getTotals()->getSubtotal());
        $this->assertThresholdsAndDeltasCorrect(
            $quoteTransfer,
            $expectedMinimumDelta,
            $expectedMaximumDelta,
            $minimumThresholdValue,
            $maximumThresholdValue,
        );
    }

    /**
     * @return array<string, array<int>>
     */
    public function quoteExpandedWithValidThresholdDeltasDataProvider(): array
    {
        return [
            'Not enough delta with one item quantity.' => [220, 500, 1, 160, 160, 220 - 160, 0],
            'Not enough delta with couple item quantity.' => [220, 500, 2, 80, 160, 220 - 160, 0],
            'Same minimum delta as totals when one item quantity.' => [220, 500, 1, 220, 220, 0, 0],
            'Same minimum delta as totals when couple item quantity.' => [220, 500, 2, 110, 220, 0, 0],
            'Enough delta when with one item quantity.' => [220, 500, 1, 221, 221, 0, 0],
            'Enough delta when with couple item quantity.' => [220, 500, 2, 150, 300, 0, 0],
            'Same maximum delta as totals when one item quantity.' => [220, 500, 1, 500, 500, 0, 0],
            'Same maximum delta as totals when couple item quantity.' => [220, 500, 2, 250, 500, 0, 0],
            'Too much delta when with one item quantity.' => [220, 500, 1, 501, 501, 0, 1],
            'Too much delta when with couple item quantity.' => [220, 500, 2, 300, 600, 0, 100],
        ];
    }

    /**
     * @return void
     */
    public function testNothingHappensWhenTotalsAreMissing(): void
    {
        // Arrange
        $this->tester->setupThresholdDependencies(100, 500);

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::QUANTITY => 1,
            ItemTransfer::UNIT_NET_PRICE => 20,
        ]))->build();

        $quoteTransfer = $this->tester->createTestQuoteTransfer()->addItem($itemTransfer)->setTotals(null);

        // Act
        $quoteTransfer = $this->tester->getFacade()->expandQuoteWithSalesOrderThresholdValues($quoteTransfer);

        // Assert
        $this->assertCount(0, $quoteTransfer->getSalesOrderThresholdValues());
    }

    /**
     * @return void
     */
    public function testExpandQuoteWithSalesOrderThresholdValuesExpandsEmptyItemsWithoutThresholds(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createTestQuoteTransfer();

        // Act
        $quoteTransfer = $this->tester->getFacade()->expandQuoteWithSalesOrderThresholdValues($quoteTransfer);

        // Assert
        $this->assertSame(0, $quoteTransfer->getTotals()->getSubtotal());
        $this->assertEmpty($quoteTransfer->getSalesOrderThresholdValues());
    }

    /**
     * @return void
     */
    public function testExpandQuoteWithSalesOrderThresholdValuesExpectsCurrencyToBeProvided(): void
    {
        // Arrange
        $this->tester->setupThresholdDependencies(100, 500);
        $quoteTransfer = $this->tester->createTestQuoteTransfer();

        $quoteTransfer->setCurrency(null);
        $this->tester->setDependency(SalesOrderThresholdDependencyProvider::SALES_ORDER_THRESHOLD_DATA_SOURCE_STRATEGIES, [
            $this->createSalesOrderThresholdDataSourceStrategyPluginMock(),
        ]);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->expandQuoteWithSalesOrderThresholdValues($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $shouldBeMinimumDelta
     * @param int $shouldBeMaximumDelta
     * @param int $minimumThresholdValue
     * @param int $maximumThresholdValue
     *
     * @return void
     */
    protected function assertThresholdsAndDeltasCorrect(
        QuoteTransfer $quoteTransfer,
        int $shouldBeMinimumDelta,
        int $shouldBeMaximumDelta,
        int $minimumThresholdValue,
        int $maximumThresholdValue
    ) {
        foreach ($quoteTransfer->getSalesOrderThresholdValues() as $salesOrderThresholdValueTransfer) {
            $expectedDelta = $this->isHardMaximumThresholdGroup($salesOrderThresholdValueTransfer) ? $shouldBeMaximumDelta : $shouldBeMinimumDelta;
            $expectedThreshold = $this->isHardMaximumThresholdGroup($salesOrderThresholdValueTransfer) ? $maximumThresholdValue : $minimumThresholdValue;

            $this->assertSame($expectedDelta, $salesOrderThresholdValueTransfer->getDeltaWithSubtotal());
            $this->assertSame($expectedThreshold, $salesOrderThresholdValueTransfer->getThreshold());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer
     *
     * @return bool
     */
    protected function isHardMaximumThresholdGroup(SalesOrderThresholdValueTransfer $salesOrderThresholdValueTransfer): bool
    {
        return $salesOrderThresholdValueTransfer->getSalesOrderThresholdType()->getThresholdGroup() === SalesOrderThresholdConfig::GROUP_HARD_MAX;
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin\SalesOrderThresholdDataSourceStrategyPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createSalesOrderThresholdDataSourceStrategyPluginMock(): SalesOrderThresholdDataSourceStrategyPluginInterface
    {
        $salesOrderThresholdDataSourceStrategyPluginMock = $this->getMockBuilder(SalesOrderThresholdDataSourceStrategyPluginInterface::class)
            ->onlyMethods(['findApplicableThresholds'])
            ->getMock();

        $salesOrderThresholdDataSourceStrategyPluginMock
            ->method('findApplicableThresholds')
            ->willReturn([(new SalesOrderThresholdValueBuilder([
                SalesOrderThresholdValueTransfer::THRESHOLD => static::THRESHOLD_STRATEGY_KEY_HARD_MAXIMUM,
            ]))->build()]);

        return $salesOrderThresholdDataSourceStrategyPluginMock;
    }
}
