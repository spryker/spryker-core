<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Sales\Business\Model\OrderItemSplit\Validation;

use SprykerFeature\Zed\Sales\Business\Model\Split\Calculator;
use SprykerFeature\Zed\Sales\Persistence;

class CalculatorTest extends \PHPUnit_Framework_TestCase
{

    public function testCalculateAmountLeft()
    {
        $calculator = new Calculator();
        $spySalesOrderItem = new Persistence\Propel\SpySalesOrderItem();
        $spySalesOrderItem->setQuantity(2);

        $quantityAmountLeft = $calculator->calculateQuantityAmountLeft($spySalesOrderItem, 1);

        $this->assertEquals(1, $quantityAmountLeft);
    }

}
