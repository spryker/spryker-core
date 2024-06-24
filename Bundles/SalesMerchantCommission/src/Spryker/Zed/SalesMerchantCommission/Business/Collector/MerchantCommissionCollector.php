<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Business\Collector;

use Generated\Shared\Transfer\SalesMerchantCommissionCollectionTransfer;

class MerchantCommissionCollector implements MerchantCommissionCollectorInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesMerchantCommissionCollectionTransfer $salesMerchantCommissionCollectionTransfer
     *
     * @return array<int, list<\Generated\Shared\Transfer\SalesMerchantCommissionTransfer>>
     */
    public function collectItemSalesMerchantCommissions(
        SalesMerchantCommissionCollectionTransfer $salesMerchantCommissionCollectionTransfer
    ): array {
        $salesMerchantCommissionTransfers = [];
        foreach ($salesMerchantCommissionCollectionTransfer->getSalesMerchantCommissions() as $salesMerchantCommissionTransfer) {
            if ($salesMerchantCommissionTransfer->getIdSalesOrderItem()) {
                $salesMerchantCommissionTransfers[$salesMerchantCommissionTransfer->getIdSalesOrderItemOrFail()][] = $salesMerchantCommissionTransfer;
            }
        }

        return $salesMerchantCommissionTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesMerchantCommissionCollectionTransfer $salesMerchantCommissionCollectionTransfer
     *
     * @return list<\Generated\Shared\Transfer\SalesMerchantCommissionTransfer>
     */
    public function collectOrderSalesMerchantCommissions(
        SalesMerchantCommissionCollectionTransfer $salesMerchantCommissionCollectionTransfer
    ): array {
        $salesMerchantCommissionTransfers = [];
        foreach ($salesMerchantCommissionCollectionTransfer->getSalesMerchantCommissions() as $salesMerchantCommissionTransfer) {
            if (!$salesMerchantCommissionTransfer->getIdSalesOrderItem()) {
                $salesMerchantCommissionTransfers[] = $salesMerchantCommissionTransfer;
            }
        }

        return $salesMerchantCommissionTransfers;
    }
}
