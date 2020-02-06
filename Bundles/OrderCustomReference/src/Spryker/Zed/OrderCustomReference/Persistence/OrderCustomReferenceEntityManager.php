<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\OrderCustomReference\Persistence\OrderCustomReferencePersistenceFactory getFactory()
 */
class OrderCustomReferenceEntityManager extends AbstractEntityManager implements OrderCustomReferenceEntityManagerInterface
{
    /**
     * @param string $orderCustomReference
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function saveOrderCustomReference(string $orderCustomReference, int $idSalesOrder): void
    {
        $salesOrderQuery = $this->getFactory()->getSalesOrderQuery();
        $salesOrderEntity = $salesOrderQuery->filterByIdSalesOrder($idSalesOrder)->findOne();

        if (!$salesOrderEntity) {
            return;
        }

        $salesOrderEntity->setOrderCustomReference($orderCustomReference);
        $salesOrderEntity->save();
    }
}
