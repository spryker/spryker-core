<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ProductOption\Business\SalesAggregator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\ProductOption\Business\SalesAggregator\SubtotalWithProductOptions;
use Unit\Spryker\Zed\ProductOption\MockProvider;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group ProductOption
 * @group Business
 * @group SalesAggregator
 * @group SubtotalWithProductOptionsTest
 */
class SubtotalWithProductOptionsTest extends MockProvider
{

    /**
     * @return void
     */
    public function testAggregateShouldSumSubtotalIncludingOptions()
    {
        $subtotalWithProductOptionAggregator = $this->createSubtotalWithProductOptionAggregator();

        $orderTransfer = new OrderTransfer();

        $totalTransfer = new TotalsTransfer();
        $totalTransfer->setSubtotal(100);

        $orderTransfer->setTotals($totalTransfer);

        $itemTransfer = new ItemTransfer();

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setSumGrossPrice(200);
        $itemTransfer->addProductOption($productOptionTransfer);

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setSumGrossPrice(200);
        $itemTransfer->addProductOption($productOptionTransfer);

        $orderTransfer->addItem($itemTransfer);

        $subtotalWithProductOptionAggregator->aggregate($orderTransfer);

        $this->assertSame(500, $orderTransfer->getTotals()->getSubtotal());
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\SalesAggregator\SubtotalWithProductOptions
     */
    protected function createSubtotalWithProductOptionAggregator()
    {
        return new SubtotalWithProductOptions();
    }

}
