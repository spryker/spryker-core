<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use PHPUnit_Framework_TestCase;
use Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderTaxAmount;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group SalesAggregator
 * @group Business
 * @group Model
 * @group OrderAmountAggregator
 * @group OrderTaxAmountTest
 */
class OrderTaxAmountTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testTaxShouldBeCalculatedFromGrandTotalUsingEffectiveTaxRate()
    {
        $orderTaxAmountAggregator = $this->createOrderTaxAmountAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $orderTaxAmountAggregator->aggregate($orderTransfer);

        $this->assertEquals(50, $orderTransfer->getTotals()->getTaxTotal()->getAmount());
    }

    /**
     * @return \Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator\OrderTaxAmount
     */
    protected function createOrderTaxAmountAggregator()
    {
        return new OrderTaxAmount();
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();

        $orderTransfer->setTotals(new TotalsTransfer());

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setTaxRate(19);
        $itemTransfer->setSumTaxAmount(25);
        $orderTransfer->addItem($itemTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setTaxRate(19);
        $itemTransfer->setSumTaxAmount(25);
        $orderTransfer->addItem($itemTransfer);

        return $orderTransfer;
    }

}
