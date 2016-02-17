<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Tax\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Sales\Business\Model\OrderAmountAggregator\ItemTax;
use Spryker\Zed\Sales\Dependency\Facade\SalesToTaxInterface;

class itemTaxTest extends \PHPUnit_Framework_TestCase
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
     * @return \Generated\Shared\Transfer\OrderTransfer
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
     * @return \Spryker\Zed\Sales\Business\Model\OrderAmountAggregator\ItemTax
     */
    protected function createItemTaxAggregator()
    {
        $taxFacadeMock = $this->createTaxFacadeMock();

        $taxFacadeMock->expects($this->exactly(2))->method('getTaxAmountFromGrossPrice')->willReturnCallback(
            function ($grosPrice, $taxRate) {
                return round($grosPrice / $taxRate); //not testing calculation. just make sure it was applied.
            }
        );

        return new ItemTax($taxFacadeMock);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Sales\Dependency\Facade\SalesToTaxInterface
     */
    protected function createTaxFacadeMock()
    {
        return $this->getMockBuilder(SalesToTaxInterface::class)->disableOriginalConstructor()->getMock();
    }

}
