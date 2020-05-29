<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Reader;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface;
use Spryker\Zed\Oms\Persistence\OmsRepositoryInterface;

class StateMachineReader implements StateMachineReaderInterface
{
    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface
     */
    protected $omsRepository;

    /**
     * @var \Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface
     */
    protected $builder;

    /**
     * @param \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface $omsRepository
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface $builder
     */
    public function __construct(OmsRepositoryInterface $omsRepository, BuilderInterface $builder)
    {
        $this->omsRepository = $omsRepository;
        $this->builder = $builder;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderItemFilterTransfer $orderItemFilterTransfer
     *
     * @return string[][]
     */
    public function getOrderItemManualEvents(OrderItemFilterTransfer $orderItemFilterTransfer): array
    {
        $manualEvents = [];

        $itemTransfers = $this->omsRepository->getOrderItems($orderItemFilterTransfer);

        foreach ($itemTransfers as $itemTransfer) {
            $manualEvents[(int)$itemTransfer->getIdSalesOrderItem()] = $this->getSingleOrderItemManualEvents($itemTransfer);
        }

        return $manualEvents;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string[]
     */
    protected function getSingleOrderItemManualEvents(ItemTransfer $itemTransfer): array
    {
        $state = $itemTransfer->getState()->getName();
        $process = $itemTransfer->getProcess();

        $processBuilder = clone $this->builder;
        $manualEvents = $processBuilder->createProcess($process)->getManualEventsBySource();

        return $manualEvents[$state] ?? [];
    }
}
