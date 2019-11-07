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
     * @param \Orm\Zed\SalesMerchantConnector\Persistence\SpySalesOrderMerchant $salesOrderMerchantEntity
     * @param \Generated\Shared\Transfer\SalesOrderMerchantTransfer $salesOrderMerchantTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderMerchantTransfer
     */
    public function mapSalesOrderMerchantEntityToSalesOrderMerchantTransfer(
        SpySalesOrderMerchant $salesOrderMerchantEntity,
        SalesOrderMerchantTransfer $salesOrderMerchantTransfer
    ): SalesOrderMerchantTransfer {
        return $salesOrderMerchantTransfer->fromArray($salesOrderMerchantEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderMerchantTransfer $salesOrderMerchantTransfer
     * @param \Orm\Zed\SalesMerchantConnector\Persistence\SpySalesOrderMerchant $salesOrderMerchantEntity
     *
     * @return \Orm\Zed\SalesMerchantConnector\Persistence\SpySalesOrderMerchant
     */
    public function mapSalesOrderMerchantTransferToSalesOrderMerchantEntity(
        SalesOrderMerchantTransfer $salesOrderMerchantTransfer,
        SpySalesOrderMerchant $salesOrderMerchantEntity
    ): SpySalesOrderMerchant {
        $salesOrderMerchantEntity->fromArray($salesOrderMerchantTransfer->modifiedToArray());

        return $salesOrderMerchantEntity;
    }
}
