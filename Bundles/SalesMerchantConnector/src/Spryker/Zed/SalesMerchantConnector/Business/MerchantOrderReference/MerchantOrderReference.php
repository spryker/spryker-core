<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Business\MerchantOrderReference;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;

class MerchantOrderReference implements MerchantOrderReferenceInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function addMerchantOrderReferenceToSalesOrderItem(
        SpySalesOrderItemEntityTransfer $salesOrderItemEntity,
        ItemTransfer $itemTransfer
    ): SpySalesOrderItemEntityTransfer {
        $merchantId = $itemTransfer->getFkMerchant();
        if (!$merchantId) {
            return $salesOrderItemEntity;
        }

        //TODO:: Switch MerchantOrderReference to OrderItemReference
        //TODO:: Add MerchantReference column to SalesOrderItem + add it from ItemTransfer

        return $salesOrderItemEntity->setMerchantOrderItemReference(sprintf(
            '%s',
            $salesOrderItemEntity->getIdSalesOrderItem()
        ));

        $salesOrderItemEntity->setMerchantRef($itemTransfer->getUuid);
    }
}
