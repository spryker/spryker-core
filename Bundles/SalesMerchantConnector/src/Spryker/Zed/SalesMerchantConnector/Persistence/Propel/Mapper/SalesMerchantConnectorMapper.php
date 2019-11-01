<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SalesOrderMerchantTransfer;
use Orm\Zed\SalesMerchantConnector\Persistence\SpySalesOrderMerchant;

class SalesMerchantConnectorMapper
{
    /**
     * @param \Orm\Zed\SalesMerchantConnector\Persistence\SpySalesOrderMerchant $salesOrderMerchant
     * @param \Generated\Shared\Transfer\SalesOrderMerchantTransfer $salesOrderMerchantTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderMerchantTransfer
     */
    public function mapSalesOrderMerchantEntityToSalesOrderMerchantTransfer(
        SpySalesOrderMerchant $salesOrderMerchant,
        SalesOrderMerchantTransfer $salesOrderMerchantTransfer
    ): SalesOrderMerchantTransfer {
        return $salesOrderMerchantTransfer->fromArray($salesOrderMerchant->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderMerchantTransfer $salesOrderMerchantTransfer
     * @param \Orm\Zed\SalesMerchantConnector\Persistence\SpySalesOrderMerchant $salesOrderMerchant
     *
     * @return \Orm\Zed\SalesMerchantConnector\Persistence\SpySalesOrderMerchant
     */
    public function mapSalesOrderMerchantTransferToSalesOrderMerchantEntity(
        SalesOrderMerchantTransfer $salesOrderMerchantTransfer,
        SpySalesOrderMerchant $salesOrderMerchant
    ): SpySalesOrderMerchant {
        $salesOrderMerchant->fromArray($salesOrderMerchantTransfer->modifiedToArray());

        return $salesOrderMerchant;
    }
}
