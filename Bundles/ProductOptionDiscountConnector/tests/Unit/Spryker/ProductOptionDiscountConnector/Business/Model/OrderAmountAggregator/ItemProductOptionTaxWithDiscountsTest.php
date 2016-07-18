<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\ItemProductOptionTaxWithDiscounts;
use Spryker\Zed\ProductOptionDiscountConnector\Dependency\Facade\ProductOptionToTaxInterface;

class ItemProductOptionTaxWithDiscountsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testTaxShouldBeAddedToItemUnitGrossPriceWithDiscountsProperty()
    {
        $itemProductOptionTaxWithDiscountsAggregator = $this->createItemProductOptionTaxWithDiscountsAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $itemProductOptionTaxWithDiscountsAggregator->aggregate($orderTransfer);

        $this->assertEquals(
            round(200 / $this->getEffectiveTaxRateForTestData()),
            $orderTransfer->getItems()[0]->getUnitTaxAmountWithProductOptionAndDiscountAmounts()
        );
    }

    /**
     * @return void
     */
    public function testTaxShouldBeAddedToItemSumGrossPriceWithDiscountsProperty()
    {
        $itemProductOptionTaxWithDiscountsAggregator = $this->createItemProductOptionTaxWithDiscountsAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $itemProductOptionTaxWithDiscountsAggregator->aggregate($orderTransfer);

        $this->assertEquals(
            round(400 / $this->getEffectiveTaxRateForTestData()),
            $orderTransfer->getItems()[0]->getSumTaxAmountWithProductOptionAndDiscountAmounts()
        );
    }

    /**
     * @return void
     */
    public function testTax()
    {
        $itemProductOptionTaxWithDiscountsAggregator = $this->createItemProductOptionTaxWithDiscountsAggregator();
        $orderTransfer = $this->createOrderTransfer();
        $itemProductOptionTaxWithDiscountsAggregator->aggregate($orderTransfer);

        $this->assertEquals(
            round(400 / $this->getEffectiveTaxRateForTestData()),
            $orderTransfer->getItems()[0]->getSumTaxAmountWithProductOptionAndDiscountAmounts()
        );
    }

    /**
     * @return float
     */
    protected function getEffectiveTaxRateForTestData()
    {
        return ((19 + 7 + 7) / 3);
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        $orderTransfer = new OrderTransfer();

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
     * @return \Spryker\Zed\ProductOptionDiscountConnector\Business\Model\OrderAmountAggregator\ItemProductOptionTaxWithDiscounts
     */
    protected function createItemProductOptionTaxWithDiscountsAggregator()
    {
        $taxFacadeBridgeMock = $this->createTaxFacadeBridgeMock();
        $taxFacadeBridgeMock->method('getTaxAmountFromGrossPrice')->willReturnCallback(
            function ($grossSum, $taxRate) {
                return round($grossSum / $taxRate); //tax forumula is not important, in this test we are testing if tax was calculated.
            }
        );

        return new ItemProductOptionTaxWithDiscounts($taxFacadeBridgeMock);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductOptionDiscountConnector\Dependency\Facade\ProductOptionToTaxInterface
     */
    protected function createTaxFacadeBridgeMock()
    {
        return $this->getMockBuilder(ProductOptionToTaxInterface::class)->disableArgumentCloning()->getMock();
    }

}
