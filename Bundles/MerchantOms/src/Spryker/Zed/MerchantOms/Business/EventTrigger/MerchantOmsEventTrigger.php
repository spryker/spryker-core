<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business\EventTrigger;

use Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\MerchantOms\Business\StateMachineProcess\StateMachineProcessReaderInterface;
use Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface;

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
     * @param \Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface $stateMachineFacade
     * @param \Spryker\Zed\MerchantOms\Business\StateMachineProcess\StateMachineProcessReaderInterface $stateMachineProcessReader
     */
    public function __construct(
        MerchantOmsToStateMachineFacadeInterface $stateMachineFacade,
        StateMachineProcessReaderInterface $stateMachineProcessReader
    ) {
        $this->stateMachineFacade = $stateMachineFacade;
        $this->stateMachineProcessReader = $stateMachineProcessReader;
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

        $stateMachineProcessTransfer = $this->stateMachineProcessReader
            ->resolveMerchantStateMachineProcess($merchantOmsTriggerRequestTransfer->getMerchant());

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
     * @return int
     */
    public function triggerEventForMerchantOrderItems(MerchantOmsTriggerRequestTransfer $merchantOmsTriggerRequestTransfer): int
    {
        $merchantOmsTriggerRequestTransfer
            ->requireMerchantOrderItems()
            ->requireMerchantOmsEventName();

        $stateMachineItemTransfers = [];

        foreach ($merchantOmsTriggerRequestTransfer->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            $stateMachineItemTransfers[] = $this->createStateMachineItem($merchantOrderItemTransfer);
        }

        return $this->stateMachineFacade->triggerEventForItems(
            $merchantOmsTriggerRequestTransfer->getMerchantOmsEventName(),
            $stateMachineItemTransfers
        );
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
