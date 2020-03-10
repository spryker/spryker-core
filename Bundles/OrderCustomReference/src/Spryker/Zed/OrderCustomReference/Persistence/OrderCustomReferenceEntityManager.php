<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @module Sales
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
        (new SpySalesOrderQuery())
            ->filterByIdSalesOrder($idSalesOrder)
            ->update([static::COLUMN_ORDER_CUSTOM_REFERENCE => $orderCustomReference]);
    }
}
