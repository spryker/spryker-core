<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Communication\Plugin\Condition;

use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\AbstractCondition;
use Spryker\Zed\Payone\Business\PayoneCommunicationFactory;
use Spryker\Zed\Payone\Business\PayoneFacade;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

/**
 * @method PayoneCommunicationFactory getFactory()
 * @method PayoneFacade getFacade()
 */
class PaymentIsPaid extends AbstractCondition
{

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        return $this->getFacade()
            ->isPaymentPaid($orderItem->getFkSalesOrder(), $orderItem->getIdSalesOrderItem());
    }

}
