<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Business\MerchantOrderReference;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;

class OrderItemReferences implements OrderItemReferencesInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function addReferencesToSalesOrderItem(
        SpySalesOrderItemEntityTransfer $salesOrderItemEntity,
        ItemTransfer $itemTransfer
    ): SpySalesOrderItemEntityTransfer {
        $salesOrderItemEntity->setOrderItemReference(
            $this->generateOrderItemReference($salesOrderItemEntity->getIdSalesOrderItem())
        );

        $merchantReference = $itemTransfer->getMerchantReference();

        if (!$merchantReference) {
            return $salesOrderItemEntity;
        }

        $salesOrderItemEntity->setMerchantReference($merchantReference);

        return $salesOrderItemEntity;
    }

    /**
     * @param int|null $idSalesOrderItem
     *
     * @return string
     */
    protected function generateOrderItemReference(?int $idSalesOrderItem): string
    {
        if (!$idSalesOrderItem) {
            return '';
        }

        return md5((string)$idSalesOrderItem);
    }
}
