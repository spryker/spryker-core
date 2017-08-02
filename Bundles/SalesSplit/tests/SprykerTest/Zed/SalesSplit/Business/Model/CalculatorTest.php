<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesSplit\Business\Model;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use PHPUnit_Framework_TestCase;
use Spryker\Zed\SalesSplit\Business\Model\Calculator;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group SalesSplit
 * @group Business
 * @group Model
 * @group CalculatorTest
 * Add your own group annotations below this line
 */
class CalculatorTest extends PHPUnit_Framework_TestCase
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
