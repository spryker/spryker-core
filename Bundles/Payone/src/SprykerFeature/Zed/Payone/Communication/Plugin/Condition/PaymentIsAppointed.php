<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Communication\Plugin\Condition;

use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition\AbstractCondition;
use SprykerFeature\Zed\Payone\Business\PayoneDependencyContainer;
use SprykerFeature\Zed\Payone\Business\PayoneFacade;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

/**
 * @method PayoneDependencyContainer getDependencyContainer()
 * @method PayoneFacade getFacade()
 */
class PaymentIsAppointed extends AbstractCondition
{

    /**
     * @param SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        $res = $this->getFacade()
            ->isPaymentAppointed($orderItem->getFkSalesOrder(), $orderItem->getIdSalesOrderItem());

        return $res;
    }

}
