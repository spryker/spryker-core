<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator;

use ArrayObject;
use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use PHPUnit_Framework_TestCase;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\OrderDiscounts;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group ProductOptionDiscountConnector
 * @group Business
 * @group Model
 * @group OrderAmountAggregator
 * @group OrderDiscountTest
 */
class OrderDiscountTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testDiscountSumShouldAggregateAmountsFromAllCalculatedDiscountsIncludingOptions()
    {
        $orderDiscountAggregator = $this->createOrderDiscountAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $orderDiscountAggregator->aggregate($orderTransfer);

        $this->assertSame(500, $orderTransfer->getCalculatedDiscounts()['test']->getSumGrossAmount());
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();

        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setDisplayName('test');
        $calculatedDiscountTransfer->setSumGrossAmount(200);

        $calculatedDiscounts = new ArrayObject();
        $calculatedDiscounts['test'] = $calculatedDiscountTransfer;

        $orderTransfer->setCalculatedDiscounts($calculatedDiscounts);

        $itemTransfer = new ItemTransfer();
        $productOptionTransfer = new ProductOptionTransfer();

        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setDisplayName('test');
        $calculatedDiscountTransfer->setSumGrossAmount(100);
        $productOptionTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setSumGrossAmount(200);
        $calculatedDiscountTransfer->setDisplayName('test');
        $productOptionTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $itemTransfer->addProductOption($productOptionTransfer);

        $orderTransfer->addItem($itemTransfer);

        $orderTransfer->setIdSalesOrder(1);

        return $orderTransfer;
    }

    /**
     * @return \Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\OrderDiscounts
     */
    protected function createOrderDiscountAggregator()
    {
        return new OrderDiscounts();
    }

}
