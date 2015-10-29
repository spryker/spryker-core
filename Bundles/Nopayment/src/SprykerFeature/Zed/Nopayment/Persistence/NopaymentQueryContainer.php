<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Nopayment\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Nopayment\Persistence\SpyNopaymentPaidQuery;
use Orm\Zed\Sales\Persistence\Base\SpySalesOrderItem;

class NopaymentQueryContainer extends AbstractQueryContainer
{

    /**
     * @param SpySalesOrderItem $orderItem
     *
     * @return SpyNopaymentPaidQuery
     */
    public function queryOrderItem(SpySalesOrderItem $orderItem)
    {
        return SpyNopaymentPaidQuery::create()
            ->findByFkSalesOrderItem($orderItem->getIdSalesOrderItem());
    }

}
