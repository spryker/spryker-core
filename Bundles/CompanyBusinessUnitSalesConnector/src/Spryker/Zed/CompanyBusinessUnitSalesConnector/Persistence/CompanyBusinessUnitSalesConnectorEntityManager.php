<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Persistence;

use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\Persistence\CompanyBusinessUnitSalesConnectorPersistenceFactory getFactory()
 */
class CompanyBusinessUnitSalesConnectorEntityManager extends AbstractEntityManager implements CompanyBusinessUnitSalesConnectorEntityManagerInterface
{
    /**
     * @param int $idSalesOrder
     * @param string $companyBusinessUnitUuid
     *
     * @return void
     */
    public function updateOrderCompanyBusinessUnitUuid(int $idSalesOrder, string $companyBusinessUnitUuid): void
    {
        $this->getFactory()
            ->getSalesOrderPropelQuery()
            ->filterByIdSalesOrder($idSalesOrder)
            ->update([ucfirst(SpySalesOrderEntityTransfer::COMPANY_BUSINESS_UNIT_UUID) => $companyBusinessUnitUuid]);
    }
}
