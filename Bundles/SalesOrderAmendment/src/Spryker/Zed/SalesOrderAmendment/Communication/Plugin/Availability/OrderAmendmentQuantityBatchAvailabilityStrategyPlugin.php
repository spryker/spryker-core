<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Communication\Plugin\Availability;

use Generated\Shared\Transfer\SellableItemsRequestTransfer;
use Generated\Shared\Transfer\SellableItemsResponseTransfer;
use Spryker\Zed\AvailabilityExtension\Dependency\Plugin\BatchAvailabilityStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesOrderAmendment\Business\SalesOrderAmendmentFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesOrderAmendment\Business\SalesOrderAmendmentBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentConfig getConfig()
 */
class OrderAmendmentQuantityBatchAvailabilityStrategyPlugin extends AbstractPlugin implements BatchAvailabilityStrategyPluginInterface
{
 /**
  * {@inheritDoc}
  * - Expects `SellableItemsRequestTransfer.quote` to be set.
  * - Requires `OriginalSalesOrderItemTransfer.originalSalesOrderItemGroupKey` to be set for each item in `SellableItemsRequestTransfer.quote.originalSalesOrderItems`.
  * - Requires `OriginalSalesOrderItemTransfer.quantity` to be set for each item in `SellableItemsRequestTransfer.quote.originalSalesOrderItems`.
  * - For each requested item which is part of the original order, it sums up the original item quantity with the available quantity from the stock.
  * - If the resulting quantity is greater than or equal to the requested quantity, the item is marked as sellable.
  * - Updates `SellableItemResponseTransfer.availableQuantity` with the calculated available quantity for each processed item.
  * - Skips items that are not part of the original sales order.
  *
  * @api
  *
  * @param \Generated\Shared\Transfer\SellableItemsRequestTransfer $sellableItemsRequestTransfer
  * @param \Generated\Shared\Transfer\SellableItemsResponseTransfer $sellableItemsResponseTransfer
  *
  * @return \Generated\Shared\Transfer\SellableItemsResponseTransfer
  */
    public function findItemsAvailabilityForStore(
        SellableItemsRequestTransfer $sellableItemsRequestTransfer,
        SellableItemsResponseTransfer $sellableItemsResponseTransfer
    ): SellableItemsResponseTransfer {
        return $this->getBusinessFactory()
            ->createSalesOrderAmendmentAvailabilityResolver()
            ->resolve($sellableItemsRequestTransfer, $sellableItemsResponseTransfer);
    }
}
