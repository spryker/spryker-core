<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\GrandTotalWithDiscounts;

class GrandTotalWithDiscountsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testGrandTotalWithDiscountsShouldSubtractDiscountAmountFromGrandTotal()
    {
        $grandTotalWithDiscountsAggregator = $this->createGrandTotalWithDiscountsAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $grandTotalWithDiscountsAggregator->aggregate($orderTransfer);

        $this->assertEquals(400, $orderTransfer->getTotals()->getGrandTotal());
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();

        $totalTransfer = new TotalsTransfer();
        $totalTransfer->setGrandTotal(500);
        $totalTransfer->setDiscountTotal(100);

        $orderTransfer->setTotals($totalTransfer);

        return $orderTransfer;
    }

    /**
     * @return \Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator\GrandTotalWithDiscounts
     */
    protected function createGrandTotalWithDiscountsAggregator()
    {
        return new GrandTotalWithDiscounts();
    }

}
