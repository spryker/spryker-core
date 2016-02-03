<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Nopayment\Communication\Plugin\Condition;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

/**
 * @method \Spryker\Zed\Nopayment\Communication\NopaymentCommunicationFactory getFactory()
 * @method \Spryker\Zed\Nopayment\Business\NopaymentFacade getFacade()
 */
class NopaymentConditionPlugin extends AbstractPlugin implements ConditionInterface
{

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        return $this->getFacade()->isPaid($orderItem);
    }

}
