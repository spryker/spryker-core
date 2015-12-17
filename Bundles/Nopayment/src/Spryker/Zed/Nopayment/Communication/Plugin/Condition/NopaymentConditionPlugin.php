<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Nopayment\Communication\Plugin\Condition;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Nopayment\Communication\NopaymentCommunicationFactory;

/**
 * @method NopaymentCommunicationFactory getFactory()
 */
class NopaymentConditionPlugin extends AbstractPlugin implements ConditionInterface
{

    /**
     * @param SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        return $this->getFactory()->createFacade()->isPaid($orderItem);
    }

}
