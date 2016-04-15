<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Order;

use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class OrderReader implements OrderReaderInterface
{

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     */
    public function __construct(SalesQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return string[]
     */
    public function getDistinctOrderStates($idSalesOrder)
    {
        $orderItems = $this->queryContainer
            ->querySalesOrderItemsByIdSalesOrder($idSalesOrder)
            ->find();

        $states = [];
        foreach ($orderItems as $orderItem) {
            $states[$orderItem->getState()->getName()] = $orderItem->getState()->getName();
        }

        return $states;
    }

}
