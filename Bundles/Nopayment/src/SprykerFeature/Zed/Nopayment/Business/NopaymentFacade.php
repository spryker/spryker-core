<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Nopayment\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;

/**
 * @method NopaymentDependencyContainer getDependencyContainer()
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
        return $this->getDependencyContainer()->createNopaymentPaid()->setAsPaid($orderItems);
    }

    /**
     * @param SpySalesOrderItem $orderItem
     *
     * @return array
     */
    public function isPaid(SpySalesOrderItem $orderItem)
    {
        return $this->getDependencyContainer()->createNopaymentPaid()->isPaid($orderItem);
    }

}
