<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Communication\Plugin\Condition;

use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\AbstractCondition;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

/**
 * @method \Spryker\Zed\Payone\Business\PayoneCommunicationFactory getFactory()
 * @method \Spryker\Zed\Payone\Business\PayoneFacade getFacade()
 */
class PaymentIsCapture extends AbstractCondition
{

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        return $this->getFacade()
            ->isPaymentCapture($orderItem->getFkSalesOrder(), $orderItem->getIdSalesOrderItem());
    }

}
