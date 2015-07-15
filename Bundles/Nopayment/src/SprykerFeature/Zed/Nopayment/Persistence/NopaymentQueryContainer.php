<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Nopayment\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Nopayment\Persistence\Propel\SpyNopaymentPaidQuery;
use SprykerFeature\Zed\Sales\Persistence\Propel\Base\SpySalesOrderItem;

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
