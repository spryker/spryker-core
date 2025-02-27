<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Plugin\Sales;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPostUpdatePluginInterface;

/**
 * @method \Spryker\Zed\ProductOption\ProductOptionConfig getConfig()
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOption\Communication\ProductOptionCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionBusinessFactory getBusinessFactory()
 */
class ProductOptionSalesOrderItemCollectionPostUpdatePlugin extends AbstractPlugin implements SalesOrderItemCollectionPostUpdatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `SalesOrderItemCollectionResponseTransfer.items.productOptions` to be provided.
     * - Requires `SalesOrderItemCollectionResponseTransfer.items.idSalesOrderItem` to be set.
     * - Deletes sales order item options related to the provided sales order items.
     * - Creates new sales order item options for the provided sales order items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function postUpdate(
        SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
    ): SalesOrderItemCollectionResponseTransfer {
        $this->getBusinessFactory()
            ->createSalesOrderItemOptionReplacer()
            ->replaceSalesOrderItemOptions(
                (new QuoteTransfer())->setItems($salesOrderItemCollectionResponseTransfer->getItems()),
            );

        return $salesOrderItemCollectionResponseTransfer;
    }
}
