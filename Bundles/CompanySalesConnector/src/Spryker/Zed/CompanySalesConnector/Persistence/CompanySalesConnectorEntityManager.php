<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySalesConnector\Persistence;

use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CompanySalesConnector\Persistence\CompanySalesConnectorPersistenceFactory getFactory()
 */
class CompanySalesConnectorEntityManager extends AbstractEntityManager implements CompanySalesConnectorEntityManagerInterface
{
    /**
     * @param int $idSalesOrder
     * @param string $companyUuid
     *
     * @return void
     */
    public function updateOrderCompanyUuid(int $idSalesOrder, string $companyUuid): void
    {
        $this->getFactory()
            ->getSalesOrderPropelQuery()
            ->filterByIdSalesOrder($idSalesOrder)
            ->update([ucfirst(SpySalesOrderEntityTransfer::COMPANY_UUID) => $companyUuid]);
    }
}
