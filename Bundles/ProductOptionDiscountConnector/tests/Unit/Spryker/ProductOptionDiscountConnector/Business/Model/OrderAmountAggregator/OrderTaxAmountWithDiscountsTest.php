<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\OrderTaxAmountWithDiscounts;
use Spryker\Zed\ProductOptionDiscountConnector\Dependency\Facade\ProductOptionToTaxInterface;

class OrderTaxAmountWithDiscountsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testAggregateTaxAmountWhenDiscountSetShouldUseEffectiveTaxRate()
    {
        $orderTaxAmountWithDiscountsAggregator = $this->createOrderTaxAmountWithDiscountAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $orderTaxAmountWithDiscountsAggregator->aggregate($orderTransfer);

        $this->assertEquals(
            round(600 / $this->getEffectiveTaxRateForTestData()),
            $orderTransfer->getTotals()->getTaxTotal()->getAmount()
        );
    }

    /**
     * @return void
     */
    public function testAggregateTaxWithDiscountsIfEffectiveRateUsedFromAverageTaxes()
    {
        $orderTaxAmountWithDiscountsAggregator = $this->createOrderTaxAmountWithDiscountAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $orderTaxAmountWithDiscountsAggregator->aggregate($orderTransfer);

        $this->assertEquals(
            $this->getEffectiveTaxRateForTestData(),
            $orderTransfer->getTotals()->getTaxTotal()->getTaxRate()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();

        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer->setGrandTotal(600);
        $orderTransfer->setTotals($totalsTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setTaxRate(19);
        $itemTransfer->setUnitGrossPriceWithProductOptionAndDiscountAmounts(200);
        $itemTransfer->setSumGrossPriceWithProductOptionAndDiscountAmounts(400);

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setTaxRate(7);
        $itemTransfer->addProductOption($productOptionTransfer);

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setTaxRate(7);
        $itemTransfer->addProductOption($productOptionTransfer);

        $orderTransfer->addItem($itemTransfer);

        return $orderTransfer;
    }

    /**
     * @return float
     */
    protected function getEffectiveTaxRateForTestData()
    {
        return ((19 + 7 + 7) / 3);
    }

    /**
     * @return \Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\OrderTaxAmountWithDiscounts
     */
    protected function createOrderTaxAmountWithDiscountAggregator()
    {
        $taxFacadeBridgeMock = $this->createTaxFacadeBridgeMock();
        $taxFacadeBridgeMock->method('getTaxAmountFromGrossPrice')->willReturnCallback(
            function ($grossSum, $taxRate) {
                return round($grossSum / $taxRate); //tax forumula is not important, in this test we are testing if tax was calculated.
            }
        );

        return new OrderTaxAmountWithDiscounts($taxFacadeBridgeMock);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductOptionDiscountConnector\Dependency\Facade\ProductOptionToTaxInterface
     */
    protected function createTaxFacadeBridgeMock()
    {
        return $this->getMockBuilder(ProductOptionToTaxInterface::class)->disableArgumentCloning()->getMock();
    }

}
