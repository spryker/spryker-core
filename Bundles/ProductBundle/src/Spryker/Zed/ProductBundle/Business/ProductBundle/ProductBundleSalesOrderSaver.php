<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\Base\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemBundle;

class ProductBundleSalesOrderSaver
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     */
    public function saveSaleOrderBundleItems(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $bundleItemsSaved = [];
        foreach ($quoteTransfer->getBundleProducts() as $itemTransfer) {

            $salesOrderItemBundleEntity = new SpySalesOrderItemBundle();
            $salesOrderItemBundleEntity->fromArray($itemTransfer->toArray());
            $salesOrderItemBundleEntity->setGrossPrice($itemTransfer->getUnitGrossPrice());
            $salesOrderItemBundleEntity->save();

            $bundleItemsSaved[$itemTransfer->getBundleItemIdentifier()] = $salesOrderItemBundleEntity->getIdSalesOrderItemBundle();
        }

        foreach ($checkoutResponse->getSaveOrder()->getOrderItems() as $itemTransfer) {
            if (!$itemTransfer->getRelatedBundleItemIdentifier()) {
                continue;
            }

            $salesOrderItemEntity = SpySalesOrderItemQuery::create()
                ->findOneByIdSalesOrderItem($itemTransfer->getIdSalesOrderItem());

            $salesOrderItemEntity->setFkSalesOrderItemBundle($bundleItemsSaved[$itemTransfer->getRelatedBundleItemIdentifier()]);
            $salesOrderItemEntity->save();
        }
    }
}
