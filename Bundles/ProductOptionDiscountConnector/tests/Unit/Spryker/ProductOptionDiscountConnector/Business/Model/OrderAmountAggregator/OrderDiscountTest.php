<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\OrderDiscounts;

class OrderDiscountTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testDiscountSumShouldAggregateAmountsFromAllCalculatedDiscountsIncludingOptions()
    {
        $orderDiscountAggregator = $this->createOrderDiscountAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $orderDiscountAggregator->aggregate($orderTransfer);

        $this->assertEquals(500, $orderTransfer->getCalculatedDiscounts()['test']->getSumGrossAmount());
    }
    
    /**
     * @return OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();

        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setDisplayName('test');
        $calculatedDiscountTransfer->setSumGrossAmount(200);

        $calculatedDiscounts = new \ArrayObject();
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
     * @return OrderDiscounts
     */
    protected function createOrderDiscountAggregator()
    {
        return new OrderDiscounts();
    }
}
