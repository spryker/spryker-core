<?php

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
        return $this->getDependencyContainer()->createNopayment()->setAsPaid($orderItems);
    }

    /**
     * @param SpySalesOrderItem $orderEntity
     *
     * @return array
     */
    public function isPaid(SpySalesOrderItem $orderEntity)
    {
        return $this->getDependencyContainer()->createNopayment()->isPaid($orderItems);
    }
}