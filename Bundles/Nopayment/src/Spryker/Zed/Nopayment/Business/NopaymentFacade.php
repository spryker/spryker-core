<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Nopayment\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

/**
 * @method NopaymentBusinessFactory getBusinessFactory()
 */
class NopaymentFacade extends AbstractFacade
{

    /**
     * @param SpySalesOrderItem[] $orderItems
     *
     * @return array
     */
    public function setAsPaid(array $orderItems)
    {
        return $this->getBusinessFactory()->createNopaymentPaid()->setAsPaid($orderItems);
    }

    /**
     * @param SpySalesOrderItem $orderItem
     *
     * @return array
     */
    public function isPaid(SpySalesOrderItem $orderItem)
    {
        return $this->getBusinessFactory()->createNopaymentPaid()->isPaid($orderItem);
    }

}
