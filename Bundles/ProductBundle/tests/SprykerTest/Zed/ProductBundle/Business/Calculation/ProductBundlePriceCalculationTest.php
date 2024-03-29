<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\Calculation;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Calculation\ProductBundlePriceCalculation;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group Calculation
 * @group ProductBundlePriceCalculationTest
 * Add your own group annotations below this line
 */
class ProductBundlePriceCalculationTest extends Unit
{
    /**
     * @return void
     */
    public function testCalculateShouldSumAllBundledItemAmountsToProductBundle(): void
    {
        $productBundlePriceCalculationMock = $this->createProductPriceCalculationMock();

        $quoteTransfer = $this->createQuoteTransfer();

        $bundleItemTransfer = new ItemTransfer();
        $bundleItemTransfer->setBundleItemIdentifier('bundle-identifier');

        $quoteTransfer->addBundleItem($bundleItemTransfer);

        $updatedQuoteTransfer = $productBundlePriceCalculationMock->calculate($quoteTransfer);

        $bundleItems = (array)$updatedQuoteTransfer->getBundleItems();
        $bundleItemTransfer = array_pop($bundleItems);

        $this->assertSame(200, $bundleItemTransfer->getUnitGrossPrice());
        $this->assertSame(400, $bundleItemTransfer->getSumGrossPrice());
        $this->assertSame(180, $bundleItemTransfer->getUnitPriceToPayAggregation());
        $this->assertSame(360, $bundleItemTransfer->getSumPriceToPayAggregation());
        $this->assertSame(20, $bundleItemTransfer->getUnitDiscountAmountAggregation());
        $this->assertSame(40, $bundleItemTransfer->getSumDiscountAmountAggregation());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\Calculation\ProductBundlePriceCalculation
     */
    protected function createProductPriceCalculationMock(): ProductBundlePriceCalculation
    {
        return new ProductBundlePriceCalculation();
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer(): OrderTransfer
    {
        $orderTransfer = new OrderTransfer();

        $bundledItems = $this->createBundledItems();
        $orderTransfer->setItems($bundledItems);

        return $orderTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(): QuoteTransfer
    {
        $quoteTransfer = new QuoteTransfer();

        $bundledItems = $this->createBundledItems();
        $quoteTransfer->setItems($bundledItems);

        return $quoteTransfer;
    }

    /**
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function createBundledItems(): ArrayObject
    {
        $bundledItems = new ArrayObject();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setUnitGrossPrice(100);
        $itemTransfer->setSumGrossPrice(200);
        $itemTransfer->setUnitSubtotalAggregation(100);
        $itemTransfer->setSumSubtotalAggregation(200);
        $itemTransfer->setUnitPriceToPayAggregation(90);
        $itemTransfer->setSumPriceToPayAggregation(180);
        $itemTransfer->setUnitDiscountAmountAggregation(10);
        $itemTransfer->setSumDiscountAmountAggregation(20);
        $itemTransfer->setIdSalesOrderItem(2);
        $itemTransfer->setRelatedBundleItemIdentifier('bundle-identifier');
        $bundledItems->append($itemTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setUnitGrossPrice(100);
        $itemTransfer->setSumGrossPrice(200);
        $itemTransfer->setUnitPriceToPayAggregation(90);
        $itemTransfer->setSumPriceToPayAggregation(180);
        $itemTransfer->setUnitDiscountAmountAggregation(10);
        $itemTransfer->setSumDiscountAmountAggregation(20);
        $itemTransfer->setIdSalesOrderItem(2);
        $itemTransfer->setRelatedBundleItemIdentifier('bundle-identifier');

        $bundledItems->append($itemTransfer);

        return $bundledItems;
    }
}
