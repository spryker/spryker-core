<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Nopayment\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Nopayment\Persistence\SpyNopaymentPaidQuery;
use Orm\Zed\Sales\Persistence\Base\SpySalesOrderItem;

class NopaymentQueryContainer extends AbstractQueryContainer
{

    /**
     * @param SpySalesOrderItem $orderItem
     *
     * @return \Orm\Zed\Nopayment\Persistence\SpyNopaymentPaidQuery
     */
    public function queryOrderItem(SpySalesOrderItem $orderItem)
    {
        return SpyNopaymentPaidQuery::create()
            ->findByFkSalesOrderItem($orderItem->getIdSalesOrderItem());
    }

}
