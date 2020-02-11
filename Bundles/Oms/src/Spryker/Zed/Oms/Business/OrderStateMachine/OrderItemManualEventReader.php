<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine;

use Generated\Shared\Transfer\ItemTransfer;

class OrderItemManualEventReader implements OrderItemManualEventReaderInterface
{
    /**
     * @var \Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface
     */
    protected $builder;

    /**
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface $builder
     */
    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $orderItemTransfers
     *
     * @return string[][]
     */
    public function getManualEventsByIdSalesOrder(iterable $orderItemTransfers): array
    {
        $events = [];
        foreach ($orderItemTransfers as $orderItemTransfer) {
            $events[$orderItemTransfer->getIdSalesOrderItem()] = $this->getManualEventsByOrderItem($orderItemTransfer);
        }

        return $events;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $orderItemTransfer
     *
     * @return string[]
     */
    protected function getManualEventsByOrderItem(ItemTransfer $orderItemTransfer): array
    {
        $orderItemTransfer->requireState();
        $stateName = $orderItemTransfer->getState()->getName();
        $processName = $orderItemTransfer->getProcess();

        $processBuilder = clone $this->builder;
        $events = $processBuilder->createProcess($processName)->getManualEventsBySource();

        if (!isset($events[$stateName])) {
            return [];
        }

        return $events[$stateName];
    }
}
