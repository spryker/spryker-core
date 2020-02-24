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
    protected const COLUMN_ORDER_CUSTOM_REFERENCE = 'OrderCustomReference';

    /**
     * @param int $idSalesOrder
     * @param string $orderCustomReference
     *
     * @return void
     */
    public function saveOrderCustomReference(int $idSalesOrder, string $orderCustomReference): void
    {
        $salesOrderQuery = $this->getFactory()
            ->getSalesOrderPropelQuery()
            ->filterByIdSalesOrder($idSalesOrder);

        if (!$salesOrderQuery->findOne()) {
            return;
        }

        $salesOrderQuery->update([static::COLUMN_ORDER_CUSTOM_REFERENCE => $orderCustomReference]);
    }
}
