<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesStatistics\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SalesStatisticTransfer;
use SprykerTest\Zed\SalesStatistics\Helper\BusinessHelper;

/**
 * Auto-generated group annotations
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
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected $spySalesOrder;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->spySalesOrder = $this->tester->create();
    }

    /**
     * @return void
     */
    public function testOrderStatisticByCountDay()
    {
        $salesStatisticTransfer = $this->tester->getLocator()->salesStatistics()->facade()->getOrderStatisticByCountDay(1);

        $this->assertInstanceOf(SalesStatisticTransfer::class, $salesStatisticTransfer);
        $this->assertEquals($salesStatisticTransfer->getValues(), [1]);
        $this->assertEquals($salesStatisticTransfer->getLabels(), [$this->spySalesOrder->getCreatedAt('Y-m-d')]);
    }

    /**
     * @return void
     */
    public function testStatusOrderStatistic()
    {
        $salesStatisticTransfer = $this->tester->getLocator()->salesStatistics()->facade()->getStatusOrderStatistic();

        $sum = array_reduce($this->spySalesOrder->getItems()->toArray(), function ($sum, $item) {
            return $sum + $item['PriceToPayAggregation'];
        }, 0);
        $sum = $sum / 100;

        $this->assertInstanceOf(SalesStatisticTransfer::class, $salesStatisticTransfer);
        $this->assertEquals($salesStatisticTransfer->getValues(), [$sum]);
        $this->assertEquals($salesStatisticTransfer->getLabels(), [BusinessHelper::DEFAULT_ITEM_STATE]);
    }

    /**
     * @return void
     */
    public function testTopOrderStatistic()
    {
        $salesStatisticTransfer = $this->tester->getLocator()->salesStatistics()->facade()->getTopOrderStatistic(1);

        $this->assertInstanceOf(SalesStatisticTransfer::class, $salesStatisticTransfer);
        $this->assertEquals($salesStatisticTransfer->getValues(), [count($this->spySalesOrder->getItems())]);
        $this->assertEquals($salesStatisticTransfer->getLabels(), [BusinessHelper::DEFAULT_PRODUCT_NAME]);
    }
}
