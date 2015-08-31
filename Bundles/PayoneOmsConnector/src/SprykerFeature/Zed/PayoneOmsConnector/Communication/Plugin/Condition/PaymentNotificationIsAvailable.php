<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneOmsConnector\Communication\Plugin\Condition;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition\AbstractCondition;
use SprykerFeature\Zed\Payone\Business\PayoneDependencyContainer;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;

/**
 * @method PayoneDependencyContainer getDependencyContainer()
 */
class PaymentNotificationIsAvailable extends AbstractCondition
{

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        $result = $this->getDependencyContainer()
            ->createPayoneFacade()
            ->isPaymentNotificationAvailable($orderItem->getFkSalesOrder(), $orderItem->getIdSalesOrderItem());

        return $result;
    }

}
