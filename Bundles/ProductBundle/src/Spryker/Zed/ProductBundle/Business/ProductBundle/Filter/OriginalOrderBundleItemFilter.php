<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Filter;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;

class OriginalOrderBundleItemFilter implements OriginalOrderBundleItemFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterOriginalSalesOrderItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $bundleItemSkus = $this->getBundleItemSkusIndexedBuSkus($quoteTransfer);

        $filteredOriginalSalesOrderItems = new ArrayObject();
        foreach ($quoteTransfer->getOriginalSalesOrderItems() as $originalSalesOrderItemTransfer) {
            if (!isset($bundleItemSkus[$originalSalesOrderItemTransfer->getSkuOrFail()])) {
                $filteredOriginalSalesOrderItems->append($originalSalesOrderItemTransfer);
            }
        }

        $quoteTransfer->setOriginalSalesOrderItems($filteredOriginalSalesOrderItems);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<string, string>
     */
    protected function getBundleItemSkusIndexedBuSkus(QuoteTransfer $quoteTransfer): array
    {
        $bundleItemSkus = [];
        foreach ($quoteTransfer->getBundleItems() as $itemTransfer) {
            $sku = $itemTransfer->getSkuOrFail();
            $bundleItemSkus[$sku] = $sku;
        }

        return $bundleItemSkus;
    }
}
