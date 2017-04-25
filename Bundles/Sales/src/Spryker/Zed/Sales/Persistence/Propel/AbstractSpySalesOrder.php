<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel;

use Orm\Zed\Sales\Persistence\Base\SpySalesOrder as BaseSpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderTotals;
use Orm\Zed\Sales\Persistence\SpySalesOrderTotalsQuery;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * Skeleton subclass for representing a row from the 'spy_sales_order' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the output directory.
 */
abstract class AbstractSpySalesOrder extends BaseSpySalesOrder
{

    /**
     * @return SpySalesOrderTotals
     */
    public function getLastOrderTotals()
    {
        $salesOrderTotalsEntity = SpySalesOrderTotalsQuery::create()
            ->orderByCreatedAt(Criteria::DESC)
            ->filterByFkSalesOrder($this->getIdSalesOrder())
            ->findOne();

        return $salesOrderTotalsEntity;
    }
}
