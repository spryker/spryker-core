<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesStatistics\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ChartDataTraceTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;

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
    protected const ITEM_NAME = 'test1';

    /**
     * @var \SprykerTest\Zed\SalesStatistics\SalesStatisticsBusinessTester
     */
    protected $tester;

    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected $spySalesOrder;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $items = [];
        $items[] = (new ItemBuilder([ItemTransfer::NAME => static::ITEM_NAME]))->build();

        $this->spySalesOrder = $this->tester->haveSalesOrderEntity($items);
    }

    /**
     * @return void
     */
    public function testOrderStatisticByCountDay()
    {
        $chartDataTraceTransfer = $this->tester->getLocator()->salesStatistics()->facade()->getOrderStatisticByCountDay(1);

        $this->assertInstanceOf(ChartDataTraceTransfer::class, $chartDataTraceTransfer);
        $this->assertEquals($chartDataTraceTransfer->getValues(), [1]);
        $this->assertEquals($chartDataTraceTransfer->getLabels(), [$this->spySalesOrder->getCreatedAt('Y-m-d')]);
    }

    /**
     * @return void
     */
    public function testStatusOrderStatistic()
    {
        $chartDataTraceTransfer = $this->tester->getLocator()->salesStatistics()->facade()->getStatusOrderStatistic();

        $sum = array_reduce($this->spySalesOrder->getItems()->toArray(), function ($sum, $item) {
            return $sum + $item['PriceToPayAggregation'];
        }, 0);
        $sum = $sum / 100;

        $this->assertInstanceOf(ChartDataTraceTransfer::class, $chartDataTraceTransfer);
        $this->assertEquals($chartDataTraceTransfer->getValues(), [$sum]);
        $this->assertEquals($chartDataTraceTransfer->getLabels(), [BusinessHelper::DEFAULT_ITEM_STATE]);
    }

    /**
     * @return void
     */
    public function testTopOrderStatistic()
    {
        // Act
        $chartDataTraceTransfer = $this->tester->getLocator()
            ->salesStatistics()
            ->facade()
            ->getTopOrderStatistic(1);

        // Assert
        $this->assertInstanceOf(ChartDataTraceTransfer::class, $chartDataTraceTransfer);
        $this->assertEquals($chartDataTraceTransfer->getValues(), [static::ITEM_NAME]);
        $this->assertEquals($chartDataTraceTransfer->getLabels(), [count($this->spySalesOrder->getItems())]);
    }
}
