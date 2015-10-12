<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayolutionOmsConnector\Communication\Plugin\Condition;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin as BaseAbstractPlugin;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;

abstract class CheckAbstractPlugin extends BaseAbstractPlugin implements ConditionInterface
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
