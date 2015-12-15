<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Communication\Plugin\Condition;

use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\AbstractCondition;
use Spryker\Zed\Payone\Business\PayoneDependencyContainer;
use Spryker\Zed\Payone\Business\PayoneFacade;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

/**
 * @method PayoneDependencyContainer getDependencyContainer()
 * @method PayoneFacade getFacade()
 */
class PaymentNotificationIsAvailable extends AbstractCondition
{

    /**
     * @param SpySalesOrderItem $orderItem
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
