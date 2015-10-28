<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Communication\Plugin\Oms\Condition;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin as BaseAbstractPlugin;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

abstract class AbstractCheckPlugin extends BaseAbstractPlugin implements ConditionInterface
{

    /**
     * @param SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        $orderEntity = $orderItem->getOrder();

        $orderTransfer = new OrderTransfer();
        $orderTransfer->fromArray($orderEntity->toArray(), $ignoreMissingProperties = true);

        $isSuccess = $this->callFacade($orderTransfer);

        return $isSuccess;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    abstract protected function callFacade(OrderTransfer $orderTransfer);

}
