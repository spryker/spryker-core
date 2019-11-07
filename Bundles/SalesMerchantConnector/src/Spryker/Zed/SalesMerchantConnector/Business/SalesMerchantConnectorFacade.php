<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Business;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SalesOrderMerchantTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesMerchantConnector\Business\SalesMerchantConnectorBusinessFactory getFactory()
 * @method \Spryker\Zed\SalesMerchantConnector\Persistence\SalesMerchantConnectorEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesMerchantConnector\Persistence\SalesMerchantConnectorRepositoryInterface getRepository()
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderMerchantTransfer $salesOrderMerchantTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderMerchantTransfer
     */
    public function createSalesOrderMerchant(SalesOrderMerchantTransfer $salesOrderMerchantTransfer): SalesOrderMerchantTransfer
    {
        return $this->getFactory()
            ->createSalesOrderMerchantWriter()
            ->createSalesOrderMerchant($salesOrderMerchantTransfer);
    }
}
