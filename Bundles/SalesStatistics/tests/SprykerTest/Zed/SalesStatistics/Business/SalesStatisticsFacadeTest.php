<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesStatistics\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SalesStatisticTransfer;

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
     * @return void
     */
    public function testOrderStatisticByCountDay()
    {
        $salesStatisticTransfer = $this->tester->getLocator()->salesStatistics()->facade()->getOrderStatisticByCountDay(1);

        $this->assertInstanceOf(SalesStatisticTransfer::class, $salesStatisticTransfer);
    }

    /**
     * @return void
     */
    public function testStatusOrderStatistic()
    {
        $salesStatisticTransfer = $this->tester->getLocator()->salesStatistics()->facade()->getStatusOrderStatistic();

        $this->assertInstanceOf(SalesStatisticTransfer::class, $salesStatisticTransfer);
    }

    /**
     * @return void
     */
    public function testTopOrderStatistic()
    {
        $salesStatisticTransfer = $this->tester->getLocator()->salesStatistics()->facade()->getTopOrderStatistic(2);

        $this->assertInstanceOf(SalesStatisticTransfer::class, $salesStatisticTransfer);
    }
}
