<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Refund\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\Refund\Communication\RefundDependencyContainer as SprykerRefundDependencyContainer;

/**
 * @method SprykerRefundDependencyContainer getDependencyContainer()
 */
class RefundFacade extends AbstractFacade
{

    /**
     * @param $orderItems
     * @param $orderEntity
     *
     * @return int
     */
    public function calculateAmount($orderItems, $orderEntity)
    {
        $this->getDependencyContainer()->createRefundManager()->calculateAmount($orderItems, $orderEntity);
    }

}
