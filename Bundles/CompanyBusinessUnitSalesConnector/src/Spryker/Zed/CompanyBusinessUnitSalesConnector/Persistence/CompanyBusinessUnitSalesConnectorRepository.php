<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Persistence;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\Persistence\CompanyBusinessUnitSalesConnectorPersistenceFactory getFactory()
 */
class CompanyBusinessUnitSalesConnectorRepository extends AbstractRepository implements CompanyBusinessUnitSalesConnectorRepositoryInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findOrderByIdSalesOrder(int $idSalesOrder): ?OrderTransfer
    {
        $salesOrderEntity = $this->getFactory()
            ->getSalesOrderPropelQuery()
            ->findOneByIdSalesOrder($idSalesOrder);

        if (!$salesOrderEntity) {
            return null;
        }

        return (new OrderTransfer())->fromArray(
            $salesOrderEntity->toArray(),
            true
        );
    }
}
