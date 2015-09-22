<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneOmsConnector\Communication\Plugin\Condition;

use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition\AbstractCondition;
use SprykerFeature\Zed\Payone\Business\PayoneDependencyContainer;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;

/**
 * @method PayoneDependencyContainer getDependencyContainer()
 */
class PaymentIsAppointed extends AbstractCondition
{

    public function check(SpySalesOrderItem $orderItem)
    {
        $res = $this->getDependencyContainer()
            ->createPayoneFacade()
            ->isPaymentAppointed($orderItem->getFkSalesOrder(), $orderItem->getIdSalesOrderItem());

        return $res;
    }

}
