<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Communication\Plugin\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\AbstractCondition;

/**
 * @method \Spryker\Zed\Payone\Business\PayoneCommunicationFactory getFactory()
 * @method \Spryker\Zed\Payone\Business\PayoneFacade getFacade()
 */
class PaymentIsOverpaid extends AbstractCondition
{

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        return $this->getFacade()
            ->isPaymentOverpaid($orderItem->getFkSalesOrder(), $orderItem->getIdSalesOrderItem());
    }

}
