<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesSplit\Business\Model;

use Codeception\Test\Unit;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
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
class CalculatorTest extends Unit
{
    /**
     * @dataProvider calculateAmountLeftProvider
     *
     * @param int|float $currentQuantity
     * @param int|float $minusQuantity
     * @param int|float $resultQuantity
     *
     * @return void
     */
    public function testCalculateAmountLeft($currentQuantity, $minusQuantity, $resultQuantity): void
    {
        $calculator = new Calculator();
        $spySalesOrderItem = new SpySalesOrderItem();
        $spySalesOrderItem->setQuantity($currentQuantity);

        $quantityAmountLeft = $calculator->calculateQuantityAmountLeft($spySalesOrderItem, $minusQuantity);

        $this->assertEquals($resultQuantity, $quantityAmountLeft);
    }

    /**
     * @return array
     */
    public function calculateAmountLeftProvider(): array
    {
        return [
            'int stock' => [2, 1, 1],
            'float stock' => [2.3658, 0.31, 2.0558],
        ];
    }
}
