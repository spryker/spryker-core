<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Business;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesMerchantConnector\Business\SalesMerchantConnectorBusinessFactory getFactory()
 */
class SalesMerchantConnectorFacade extends AbstractFacade implements SalesMerchantConnectorFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function addMerchantOrderReferenceToSalesOrderItem(
        SpySalesOrderItemEntityTransfer $salesOrderItemEntity,
        ItemTransfer $itemTransfer
    ): SpySalesOrderItemEntityTransfer {
        return $this->getFactory()
            ->createMerchantOrderReference()
            ->addMerchantOrderReferenceToSalesOrderItem($salesOrderItemEntity, $itemTransfer);
    }
}
