<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Business\Reader;

use Generated\Shared\Transfer\SalesMerchantCommissionCollectionTransfer;

interface SalesMerchantCommissionReaderInterface
{
    /**
     * @param list<int> $salesOrderItemIds
     *
     * @return list<\Generated\Shared\Transfer\SalesMerchantCommissionTransfer>
     */
    public function getSalesMerchantCommissionsBySalesOrderItemIds(array $salesOrderItemIds): array;

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\SalesMerchantCommissionCollectionTransfer
     */
    public function getSalesMerchantCommissionsByIdSalesOrder(int $idSalesOrder): SalesMerchantCommissionCollectionTransfer;
}
