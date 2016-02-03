<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Nopayment\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

/**
 * @method \Spryker\Zed\Nopayment\Business\NopaymentBusinessFactory getFactory()
 */
class NopaymentFacade extends AbstractFacade
{

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     *
     * @return array
     */
    public function setAsPaid(array $orderItems)
    {
        return $this->getFactory()->createNopaymentPaid()->setAsPaid($orderItems);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return array
     */
    public function isPaid(SpySalesOrderItem $orderItem)
    {
        return $this->getFactory()->createNopaymentPaid()->isPaid($orderItem);
    }

}
