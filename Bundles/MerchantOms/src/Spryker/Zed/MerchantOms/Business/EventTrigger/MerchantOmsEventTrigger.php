<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business\EventTrigger;

use Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Zed\MerchantOms\Business\StateMachineProcess\StateMachineProcessReaderInterface;
use Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface;
use Spryker\Zed\MerchantOms\MerchantOmsConfig;

class MerchantOmsEventTrigger implements MerchantOmsEventTriggerInterface
{
    /**
     * @var \Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface
     */
    protected $stateMachineFacade;

    /**
     * @var \Spryker\Zed\MerchantOms\Business\StateMachineProcess\StateMachineProcessReaderInterface
     */
    protected $stateMachineProcessReader;

    /**
     * @var \Spryker\Zed\MerchantOms\MerchantOmsConfig
     */
    protected $merchantOmsConfig;

    /**
     * @param \Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface $stateMachineFacade
     * @param \Spryker\Zed\MerchantOms\Business\StateMachineProcess\StateMachineProcessReaderInterface $stateMachineProcessReader
     * @param \Spryker\Zed\MerchantOms\MerchantOmsConfig $merchantOmsConfig
     */
    public function __construct(
        MerchantOmsToStateMachineFacadeInterface $stateMachineFacade,
        StateMachineProcessReaderInterface $stateMachineProcessReader,
        MerchantOmsConfig $merchantOmsConfig
    ) {
        $this->stateMachineFacade = $stateMachineFacade;
        $this->stateMachineProcessReader = $stateMachineProcessReader;
        $this->merchantOmsConfig = $merchantOmsConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer
     *
     * @return void
     */
    public function triggerForNewMerchantOrderItems(MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer): void
    {
        $merchantOmsTriggerRequestTransfer
            ->requireMerchantOrderItems()
            ->requireMerchant()
            ->getMerchant()
                ->requireMerchantReference();

        $stateMachineProcessTransfer = $this->resolveStateMachineProcess($merchantOmsTriggerRequestTransfer->getMerchant());

        foreach ($merchantOmsTriggerRequestTransfer->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            $this->stateMachineFacade->triggerForNewStateMachineItem(
                $stateMachineProcessTransfer,
                $merchantOrderItemTransfer->getIdMerchantOrderItem()
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer
     *
     * @return void
     */
    public function triggerEventForMerchantOrderItems(MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer): void
    {
        $merchantOmsTriggerRequestTransfer->requireMerchantOrderItems();
        $merchantOmsTriggerRequestTransfer->requireMerchantOmsEventName();

        $stateMachineItemTransfers = [];

        foreach ($merchantOmsTriggerRequestTransfer->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            $stateMachineItemTransfers[] = $this->createStateMachineItem($merchantOrderItemTransfer);
        }

        $this->stateMachineFacade->triggerEventForItems(
            $merchantOmsTriggerRequestTransfer->getMerchantOmsEventName(),
            $stateMachineItemTransfers
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\StateMachineProcessTransfer
     */
    protected function resolveStateMachineProcess(MerchantTransfer $merchantTransfer): StateMachineProcessTransfer
    {
        $stateMachineProcessTransfer = $this->stateMachineProcessReader->findStateMachineProcessByMerchant($merchantTransfer);

        if (!$stateMachineProcessTransfer) {
            $stateMachineProcessTransfer = (new StateMachineProcessTransfer())
                ->setStateMachineName($this->merchantOmsConfig->getMerchantOmsStateMachineName())
                ->setProcessName($this->merchantOmsConfig->getMerchantOmsDefaultProcessName());
        }

        return $stateMachineProcessTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemTransfer $merchantOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    protected function createStateMachineItem(MerchantOrderItemTransfer $merchantOrderItemTransfer): StateMachineItemTransfer
    {
        return (new StateMachineItemTransfer())
            ->setIdItemState($merchantOrderItemTransfer->getFkStateMachineItemState())
            ->setIdentifier($merchantOrderItemTransfer->getIdMerchantOrderItem());
    }
}
