<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Persistence;

use Generated\Shared\Transfer\SalesOrderMerchantTransfer;
use Orm\Zed\SalesMerchantConnector\Persistence\SpySalesOrderMerchant;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SalesMerchantConnector\Persistence\SalesMerchantConnectorPersistenceFactory getFactory()
 */
class SalesMerchantConnectorEntityManager extends AbstractEntityManager implements SalesMerchantConnectorEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderMerchantTransfer $orderMerchantTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderMerchantTransfer
     */
    public function createSalesOrderMerchant(SalesOrderMerchantTransfer $orderMerchantTransfer): SalesOrderMerchantTransfer
    {
        $salesMerchantConnectorMapper = $this->getFactory()->createSalesMerchantConnectorMapper();

        $salesOrderMerchant = $salesMerchantConnectorMapper->mapSalesOrderMerchantTransferToSalesOrderMerchantEntity(
            $orderMerchantTransfer,
            new SpySalesOrderMerchant()
        );
        $salesOrderMerchant->save();

        return $salesMerchantConnectorMapper->mapSalesOrderMerchantEntityToSalesOrderMerchantTransfer(
            $salesOrderMerchant,
            new SalesOrderMerchantTransfer()
        );
    }
}
