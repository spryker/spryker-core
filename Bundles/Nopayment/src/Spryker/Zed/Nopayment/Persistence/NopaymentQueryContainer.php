<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Nopayment\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Sales\Persistence\Base\SpySalesOrderItem;

/**
 * @method NopaymentPersistenceFactory getFactory()
 */
class NopaymentQueryContainer extends AbstractQueryContainer
{

    /**
     * @param \Orm\Zed\Sales\Persistence\Base\SpySalesOrderItem $orderItem
     *
     * @return \Orm\Zed\Nopayment\Persistence\SpyNopaymentPaidQuery
     */
    public function queryOrderItem(SpySalesOrderItem $orderItem)
    {
        return $this->getFactory()->createNopaymentPaidQuery()
            ->findByFkSalesOrderItem($orderItem->getIdSalesOrderItem());
    }

}
