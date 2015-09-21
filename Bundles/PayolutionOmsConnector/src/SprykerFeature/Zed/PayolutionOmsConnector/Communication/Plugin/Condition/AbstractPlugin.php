<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayolutionOmsConnector\Communication\Plugin\Condition;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin as BaseAbstractPlugin;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;

abstract class AbstractPlugin extends BaseAbstractPlugin implements ConditionInterface
{

    const NAME = 'AbstractPlugin';

    /**
     * @var array[]
     */
    private static $resultCache = [];

    /**
     * @param SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        $orderEntity = $orderItem->getOrder();

        if (isset(self::$resultCache[$this->getName()][$orderEntity->getPrimaryKey()])) {
            return self::$resultCache[$this->getName()][$orderEntity->getPrimaryKey()];
        }

        $orderTransfer = new OrderTransfer();
        $orderTransfer->fromArray($orderEntity->toArray(), $ignoreMissingProperties = true);

        $isSuccess = $this->callFacade($orderTransfer);
        self::$resultCache[$this->getName()][$orderEntity->getPrimaryKey()] = $isSuccess;

        return $isSuccess;
    }

    /**
     * @return string
     */
    protected function getName()
    {
        return static::NAME;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    abstract protected function callFacade(OrderTransfer $orderTransfer);

}
