<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */


namespace Unit\Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\DiscountTotalAmount;

class DiscountTotalAmountTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function testDiscountTotalAmountWhenDiscountIsPresentInOptionsShouldSumAmounts()
    {
        $discountTotalAmountAggregator = $this->createDiscountTotalAmountAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $discountTotalAmountAggregator->aggregate($orderTransfer);

        $this->assertEquals(400, $orderTransfer->getTotals()->getDiscountTotal());
    }

    /**
     * @return void
     */
    public function testWhenDiscountUsedThenItemShouldHaveDiscountsApplied()
    {
        $discountTotalAmountAggregator = $this->createDiscountTotalAmountAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $discountTotalAmountAggregator->aggregate($orderTransfer);

        $this->assertEquals(100, $orderTransfer->getItems()[0]->getSumGrossPriceWithProductOptionAndDiscountAmounts());
    }

    /**
     * @return void
     */
    public function testRefundableAmountShouldBeAfterDiscounts()
    {
        $discountTotalAmountAggregator = $this->createDiscountTotalAmountAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $discountTotalAmountAggregator->aggregate($orderTransfer);

        $refundableAmount = $orderTransfer->getItems()[0]->getRefundableAmount();

        $this->assertEquals(100, $refundableAmount);
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();

        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer->setDiscountTotal(100);

        $orderTransfer->setTotals($totalsTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setRefundableAmount(400);
        $itemTransfer->setUnitGrossPriceWithProductOptions(200);
        $itemTransfer->setSumGrossPriceWithProductOptions(400);
        $productOptionTransfer = new ProductOptionTransfer();

        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setSumGrossAmount(100);
        $productOptionTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $calculatedDiscountTransfer = new CalculatedDiscountTransfer();
        $calculatedDiscountTransfer->setSumGrossAmount(200);
        $productOptionTransfer->addCalculatedDiscount($calculatedDiscountTransfer);

        $itemTransfer->addProductOption($productOptionTransfer);

        $orderTransfer->addItem($itemTransfer);

        $orderTransfer->setIdSalesOrder(1);

        return $orderTransfer;
    }

    /**
     * @return \Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\DiscountTotalAmount
     */
    protected function createDiscountTotalAmountAggregator()
    {
        return new DiscountTotalAmount();
    }
}
