<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use PHPUnit_Framework_TestCase;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\TaxCalculator\OrderTaxAmountWithDiscounts;
use Spryker\Zed\ProductOptionDiscountConnector\Dependency\Facade\ProductOptionToTaxInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group ProductOptionDiscountConnector
 * @group Business
 * @group Model
 * @group OrderAmountAggregator
 * @group OrderTaxAmountWithDiscountsTest
 */
class OrderTaxAmountWithDiscountsTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testAggregateTaxAmountShouldSumAllItemTaxes()
    {
        $orderTaxAmountWithDiscountsAggregator = $this->createOrderTaxAmountWithDiscountAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $orderTaxAmountWithDiscountsAggregator->aggregate($orderTransfer);

        $this->assertSame(186, $orderTransfer->getTotals()->getTaxTotal()->getAmount());
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();

        $orderTransfer->setTotals(new TotalsTransfer());

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSumTaxAmountWithProductOptionAndDiscountAmounts(63);

        $orderTransfer->addItem($itemTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSumTaxAmountWithProductOptionAndDiscountAmounts(123);

        $orderTransfer->addItem($itemTransfer);

        return $orderTransfer;
    }

    /**
     * @return \Spryker\Zed\ProductOptionDiscountConnector\Business\Model\TaxCalculator\OrderTaxAmountWithDiscounts
     */
    protected function createOrderTaxAmountWithDiscountAggregator()
    {
        return new OrderTaxAmountWithDiscounts();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductOptionDiscountConnector\Dependency\Facade\ProductOptionToTaxInterface
     */
    protected function createTaxFacadeBridgeMock()
    {
        return $this->getMockBuilder(ProductOptionToTaxInterface::class)->disableArgumentCloning()->getMock();
    }

}
