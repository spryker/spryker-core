<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Tax\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Tax\Business\Model\OrderAmountAggregator\ItemTax;
use Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface;

class ItemTaxTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testItemTaxIsAppliedToUnitTaxForItem()
    {
        $itemTaxAggregator = $this->createItemTaxAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $itemTaxAggregator->aggregate($orderTransfer);

        $this->assertEquals(round(10 / 19), $orderTransfer->getItems()[0]->getUnitTaxAmount());
    }

    /**
     * @return void
     */
    public function testItemTaxIsAppliedToSumTaxForItem()
    {
        $itemTaxAggregator = $this->createItemTaxAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $itemTaxAggregator->aggregate($orderTransfer);

        $this->assertEquals(round(20 / 19), $orderTransfer->getItems()[0]->getSumTaxAmount());
    }

    /**
     * @return void
     */
    public function testItemTaxIsAppliedToSumTaxForExpense()
    {
        $itemTaxAggregator = $this->createItemTaxAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $itemTaxAggregator->aggregate($orderTransfer);

        $this->assertEquals(round(20 / 19), $orderTransfer->getExpenses()[0]->getSumTaxAmount());
    }

    /**
     * @return void
     */
    public function testItemTaxIsAppliedToUnitTaxForExpense()
    {
        $itemTaxAggregator = $this->createItemTaxAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $itemTaxAggregator->aggregate($orderTransfer);

        $this->assertEquals(round(20 / 19), $orderTransfer->getExpenses()[0]->getUnitTaxAmount());
    }
    
    /**
     * @return OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setTaxRate(19);
        $itemTransfer->setUnitGrossPrice(10);
        $itemTransfer->setSumGrossPrice(20);
        $orderTransfer->addItem($itemTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setTaxRate(19);
        $expenseTransfer->setUnitGrossPrice(10);
        $expenseTransfer->setSumGrossPrice(20);
        $orderTransfer->addExpense($expenseTransfer);

        return $orderTransfer;
    }

    /**
     * @return ItemTax
     */
    protected function createItemTaxAggregator()
    {
        $priceHelperMock = $this->createPriceHelperMock();

        $priceHelperMock->expects($this->exactly(4))->method('getTaxValueFromPrice')->willReturnCallback(
            function ($grosPrice, $taxRate) {
                return round($grosPrice / $taxRate); //not testing calculation. just make sure it was applied.
            }
        );

        return new ItemTax($priceHelperMock);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|PriceCalculationHelperInterface
     */
    protected function createPriceHelperMock()
    {
        return $this->getMockBuilder(PriceCalculationHelperInterface::class)->disableOriginalConstructor()->getMock();
    }
}
