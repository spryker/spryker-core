<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business\MerchantOms;

use Generated\Shared\Transfer\MerchantOmsEventTransfer;
use Generated\Shared\Transfer\MerchantOmsProcessCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderItemResponseTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderResponseTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Zed\MerchantOms\Business\MerchantOmsProcess\MerchantOmsProcessReaderInterface;
use Spryker\Zed\MerchantOms\Business\MerchantOrderItem\MerchantOrderItemMapperInterface;
use Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface;
use Spryker\Zed\MerchantOms\MerchantOmsConfig;

class MerchantOmsEventDispatcher implements MerchantOmsEventDispatcherInterface
{
    /**
     * @var \Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface
     */
    protected $stateMachineFacade;

    /**
     * @var \Spryker\Zed\MerchantOms\Business\MerchantOrderItem\MerchantOrderItemMapperInterface
     */
    protected $merchantOrderItemMapper;

    /**
     * @var \Spryker\Zed\MerchantOms\Business\MerchantOmsProcess\MerchantOmsProcessReaderInterface
     */
    protected $merchantOmsProcessReader;

    /**
     * @var \Spryker\Zed\MerchantOms\MerchantOmsConfig
     */
    protected $merchantOmsConfig;

    /**
     * @param \Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface $stateMachineFacade
     * @param \Spryker\Zed\MerchantOms\Business\MerchantOrderItem\MerchantOrderItemMapperInterface $merchantOrderItemMapper
     * @param \Spryker\Zed\MerchantOms\Business\MerchantOmsProcess\MerchantOmsProcessReaderInterface $merchantOmsProcessReader
     * @param \Spryker\Zed\MerchantOms\MerchantOmsConfig $merchantOmsConfig
     */
    public function __construct(
        MerchantOmsToStateMachineFacadeInterface $stateMachineFacade,
        MerchantOrderItemMapperInterface $merchantOrderItemMapper,
        MerchantOmsProcessReaderInterface $merchantOmsProcessReader,
        MerchantOmsConfig $merchantOmsConfig
    ) {
        $this->stateMachineFacade = $stateMachineFacade;
        $this->merchantOrderItemMapper = $merchantOrderItemMapper;
        $this->merchantOmsProcessReader = $merchantOmsProcessReader;
        $this->merchantOmsConfig = $merchantOmsConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderResponseTransfer
     */
    public function dispatchNewMerchantOrderEvent(MerchantOrderTransfer $merchantOrderTransfer): MerchantOrderResponseTransfer
    {
        $transitionCount = 0;
        $stateMachineProcessTransfer = $this->getStateMachineProcessTransferFromMerchantOrder($merchantOrderTransfer);

        foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            $transitionCount += $this->stateMachineFacade->triggerForNewStateMachineItem(
                $stateMachineProcessTransfer,
                $merchantOrderItemTransfer->getIdMerchantOrderItem()
            );
        }

        return (new MerchantOrderResponseTransfer())->setIsSuccessful((bool)$transitionCount);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemTransfer $merchantOrderItemTransfer
     * @param \Generated\Shared\Transfer\MerchantOmsEventTransfer $merchantOmsEventTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemResponseTransfer
     */
    public function dispatchMerchantOrderItemEvent(
        MerchantOrderItemTransfer $merchantOrderItemTransfer,
        MerchantOmsEventTransfer $merchantOmsEventTransfer
    ): MerchantOrderItemResponseTransfer {
        $transitionCount = $this->stateMachineFacade->triggerEvent(
            $merchantOmsEventTransfer->getEventName(),
            $this->merchantOrderItemMapper->mapMerchantOrderItemTransferToStateMachineItemTransfer(
                $merchantOrderItemTransfer,
                new StateMachineItemTransfer()
            )
        );

        return (new MerchantOrderItemResponseTransfer())->setIsSuccessful((bool)$transitionCount);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer $merchantOrderItemCollectionTransfer
     * @param \Generated\Shared\Transfer\MerchantOmsEventTransfer $merchantOmsEventTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemResponseTransfer
     */
    public function dispatchMerchantOrderItemsEvent(
        MerchantOrderItemCollectionTransfer $merchantOrderItemCollectionTransfer,
        MerchantOmsEventTransfer $merchantOmsEventTransfer
    ): MerchantOrderItemResponseTransfer {
        $stateMachineItemTransfers = [];

        foreach ($merchantOrderItemCollectionTransfer->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            $stateMachineItemTransfers[] = $this->merchantOrderItemMapper->mapMerchantOrderItemTransferToStateMachineItemTransfer(
                $merchantOrderItemTransfer,
                new StateMachineItemTransfer()
            );
        }

        $transitionCount = $this->stateMachineFacade->triggerEventForItems(
            $merchantOmsEventTransfer->getEventName(),
            $stateMachineItemTransfers
        );

        return (new MerchantOrderItemResponseTransfer())->setIsSuccessful((bool)$transitionCount);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\StateMachineProcessTransfer
     */
    protected function getStateMachineProcessTransferFromMerchantOrder(MerchantOrderTransfer $merchantOrderTransfer): StateMachineProcessTransfer
    {
        $merchantOmsProcess = $this->merchantOmsProcessReader->getMerchantOmsProcess(
            (new MerchantOmsProcessCriteriaFilterTransfer())->setMerchantReference($merchantOrderTransfer->getMerchantReference())
        );

        return (new StateMachineProcessTransfer())
            ->setProcessName($merchantOmsProcess->getProcessName())
            ->setStateMachineName($this->merchantOmsConfig->getMerchantOmsStateMachineName());
    }
}
