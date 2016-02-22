<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Communication\Plugin\Condition;

use Spryker\Zed\Kernel\Communication\AbstractPlugin as BaseAbstractPlugin;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Generated\Shared\Transfer\OrderTransfer;

/**
 * @method \Spryker\Zed\Payone\Business\PayoneCommunicationFactory getFactory()
 */
abstract class AbstractPlugin extends BaseAbstractPlugin implements ConditionInterface
{

    const NAME = 'AbstractPlugin';

    /**
     * @var array
     */
    private static $resultCache = [];

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
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
