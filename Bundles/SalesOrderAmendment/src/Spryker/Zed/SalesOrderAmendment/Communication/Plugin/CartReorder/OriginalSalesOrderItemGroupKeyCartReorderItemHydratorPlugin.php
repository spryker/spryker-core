<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Communication\Plugin\CartReorder;

use Generated\Shared\Transfer\CartReorderTransfer;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderItemHydratorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentConfig getConfig()
 * @method \Spryker\Zed\SalesOrderAmendment\Business\SalesOrderAmendmentFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesOrderAmendment\Business\SalesOrderAmendmentBusinessFactory getBusinessFactory()
 */
class OriginalSalesOrderItemGroupKeyCartReorderItemHydratorPlugin extends AbstractPlugin implements CartReorderItemHydratorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `CartReorderTransfer.orderItems.idSalesOrderItem` to be set.
     * - Requires `CartReorderTransfer.orderItems.sku` to be set.
     * - Requires `CartReorderTransfer.orderItems.quantity` to be set.
     * - Requires `CartReorderTransfer.orderItems.groupKey` to be set.
     * - Requires `CartReorderTransfer.reorderItems.idSalesOrderItem` to be set.
     * - Hydrates `CartReorderTransfer.reorderItems.originalSalesOrderItemGroupKey` with the original sales order item group key if item with provided `idSalesOrderItem` exists.
     * - Adds new reorder item with sku, quantity, ID sales order item and original sales order item group key properties set to `CartReorderTransfer.reorderItems` otherwise.
     * - Returns expanded `CartReorderTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function hydrate(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer
    {
        return $this->getBusinessFactory()
            ->createCartReorderItemHydrator()
            ->hydrateOriginalSalesOrderItemGroupKeys($cartReorderTransfer);
    }
}
