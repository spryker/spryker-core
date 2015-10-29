<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Communication\Plugin\Condition;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin as BaseAbstractPlugin;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;
use SprykerFeature\Zed\Payone\Business\PayoneDependencyContainer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Generated\Shared\Transfer\OrderTransfer;

/**
 * @method PayoneDependencyContainer getDependencyContainer()
 */
abstract class AbstractPlugin extends BaseAbstractPlugin implements ConditionInterface
{

    const NAME = 'AbstractPlugin';

    /**
     * @var array
     */
    private static $resultCache = [];

    /**
     * @param SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        $order = $orderItem->getOrder();

        if (isset(self::$resultCache[$this->getName()][$order->getPrimaryKey()])) {
            return self::$resultCache[$this->getName()][$order->getPrimaryKey()];
        }

        $orderTransfer = new OrderTransfer();
        $orderTransfer->fromArray($order->toArray(), true);

        $isSuccess = $this->callFacade($orderTransfer);
        self::$resultCache[$order->getPrimaryKey()] = $isSuccess;

        return $isSuccess;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    abstract protected function callFacade(OrderTransfer $orderTransfer);

    /**
     * @return string
     */
    protected function getName()
    {
        return self::NAME;
    }

}
