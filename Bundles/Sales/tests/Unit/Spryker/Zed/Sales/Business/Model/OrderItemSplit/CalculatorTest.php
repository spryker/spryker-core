<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Sales\Business\Model\OrderItemSplit\Validation;

use Spryker\Zed\Sales\Business\Model\Split\Calculator;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

class CalculatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCalculateAmountLeft()
    {
        $calculator = new Calculator();
        $spySalesOrderItem = new SpySalesOrderItem();
        $spySalesOrderItem->setQuantity(2);

        $quantityAmountLeft = $calculator->calculateQuantityAmountLeft($spySalesOrderItem, 1);

        $this->assertEquals(1, $quantityAmountLeft);
    }

}
