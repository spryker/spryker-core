<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Business;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SalesOrderMerchantSaveTransfer;
use Generated\Shared\Transfer\SalesOrderMerchantTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesMerchantConnector\Business\SalesMerchantConnectorBusinessFactory getFactory()
 * @method \Spryker\Zed\SalesMerchantConnector\Persistence\SalesMerchantConnectorEntityManagerInterface getEntityManager()
 */
class SalesMerchantConnectorFacade extends AbstractFacade implements SalesMerchantConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function expandOrderItemWithReferences(
        SpySalesOrderItemEntityTransfer $salesOrderItemEntity,
        ItemTransfer $itemTransfer
    ): SpySalesOrderItemEntityTransfer {
        return $this->getFactory()
            ->createOrderItemExpander()
            ->expandOrderItemWithReferences($salesOrderItemEntity, $itemTransfer);
    }

    /**
     * Specification:
     * - Create relation between order and merchant and save data to `spy_sales_order_merchant`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderMerchantSaveTransfer $salesOrderMerchantSaveTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderMerchantTransfer
     */
    public function createSalesOrderMerchant(SalesOrderMerchantSaveTransfer $salesOrderMerchantSaveTransfer): SalesOrderMerchantTransfer
    {
        return $this->getFactory()
            ->createSalesOrderMerchantWriter()
            ->createSalesOrderMerchant($salesOrderMerchantSaveTransfer);
    }
}
