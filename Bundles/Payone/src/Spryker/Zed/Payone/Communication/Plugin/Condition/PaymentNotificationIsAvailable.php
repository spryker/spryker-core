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
class PaymentNotificationIsAvailable extends AbstractCondition
{

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        $result = $this->getFacade()
            ->isPaymentNotificationAvailable($orderItem->getFkSalesOrder(), $orderItem->getIdSalesOrderItem());

        return $result;
    }

}
