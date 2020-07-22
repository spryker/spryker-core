<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesStatistics\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ChartDataTraceTransfer;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;
use SprykerTest\Zed\SalesStatistics\SalesStatisticsBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesStatistics
 * @group Business
 * @group Facade
 * @group SalesStatisticsFacadeTest
 * Add your own group annotations below this line
 */
class SalesStatisticsFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesStatistics\SalesStatisticsBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testOrderStatisticByCountDay(): void
    {
        // Arrange
        $spySalesOrder = $this->tester->haveOrderWithOneItem();

        // Act
        $chartDataTraceTransfer = $this->tester->getFacade()->getOrderStatisticByCountDay(1);

        // Assert
        $values = $chartDataTraceTransfer->getValues();
        $latestValue = array_pop($values);

        $labels = $chartDataTraceTransfer->getLabels();
        $latestLabel = array_pop($labels);

        $this->assertInstanceOf(ChartDataTraceTransfer::class, $chartDataTraceTransfer);
        $this->assertSame(1, $latestValue);
        $this->assertSame($spySalesOrder->getCreatedAt('Y-m-d'), $latestLabel);
    }

    /**
     * @return void
     */
    public function testStatusOrderStatistic(): void
    {
        // Arrange
        $spySalesOrder = $this->tester->haveOrderWithOneItem();

        // Act
        $chartDataTraceTransfer = $this->tester->getFacade()->getStatusOrderStatistic();
        $sum = array_reduce($spySalesOrder->getItems()->toArray(), function ($sum, $item) {
            return $sum + $item['PriceToPayAggregation'];
        }, 0);
        $sum = $sum / 100;

        // Assert
        $values = $chartDataTraceTransfer->getValues();
        $latestValue = array_pop($values);

        $labels = $chartDataTraceTransfer->getLabels();
        $latestLabel = array_pop($labels);

        $this->assertInstanceOf(ChartDataTraceTransfer::class, $chartDataTraceTransfer);
        $this->assertSame($sum, $latestValue);
        $this->assertSame(BusinessHelper::DEFAULT_ITEM_STATE, $latestLabel);
    }

    /**
     * @return void
     */
    public function testTopOrderStatistic(): void
    {
        $this->markTestIncomplete('Test code and code behind MUSt be fixed.');

        // Arrange
        $spySalesOrder = $this->tester->haveOrderWithTwoItems();

        // Act
        $chartDataTraceTransfer = $this->tester->getFacade()->getTopOrderStatistic(10);

        // Assert
        $values = $chartDataTraceTransfer->getValues();
        $latestValue = array_pop($values);

        $labels = $chartDataTraceTransfer->getLabels();
        $latestLabel = array_pop($labels);

        $this->assertInstanceOf(ChartDataTraceTransfer::class, $chartDataTraceTransfer);
        $this->assertSame(SalesStatisticsBusinessTester::ITEM_NAME, $latestValue);
        $this->assertSame(count($spySalesOrder->getItems()), $latestLabel);
    }
}
