<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Communication\Extractor;

use Generated\Shared\Transfer\OrderTransfer;

class SalesOrderItemExtractor implements SalesOrderItemExtractorInterface
{
    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $salesOrderItemEntities
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function extractSalesOrderItemsFromOrderBySalesOrderItemIds(
        array $salesOrderItemEntities,
        OrderTransfer $orderTransfer
    ): array {
        $salesOrderItemIdsMap = $this->extractSalesOrderItemIdsMap($salesOrderItemEntities);

        $salesOrderItemTransfers = [];
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if (isset($salesOrderItemIdsMap[$itemTransfer->getIdSalesOrderItem()])) {
                $salesOrderItemTransfers[] = $itemTransfer;
            }
        }

        return $salesOrderItemTransfers;
    }

    /**
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $salesOrderItemEntities
     *
     * @return array<int, int>
     */
    protected function extractSalesOrderItemIdsMap(array $salesOrderItemEntities): array
    {
        $salesOrderItemIdsMap = [];
        foreach ($salesOrderItemEntities as $salesOrderItemEntity) {
            $idSalesOrderItem = $salesOrderItemEntity->getIdSalesOrderItem();
            $salesOrderItemIdsMap[$idSalesOrderItem] = $idSalesOrderItem;
        }

        return $salesOrderItemIdsMap;
    }
}
