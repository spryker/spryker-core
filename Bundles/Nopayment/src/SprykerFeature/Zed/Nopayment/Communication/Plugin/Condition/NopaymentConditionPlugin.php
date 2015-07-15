<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Nopayment\Communication\Plugin\Condition;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;
use SprykerFeature\Zed\Nopayment\Communication\NopaymentDependencyContainer;

/**
 * @method NopaymentDependencyContainer getDependencyContainer()
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
        return $this->getDependencyContainer()->createFacade()->isPaid($orderItem);
    }

}
