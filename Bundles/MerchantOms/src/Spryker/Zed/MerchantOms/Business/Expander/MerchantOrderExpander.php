<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business\Expander;

use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface;
use Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface;

class MerchantOrderExpander implements MerchantOrderExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface
     */
    protected $stateMachineFacade;

    /**
     * @var \Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface
     */
    protected $merchantOmsRepository;

    /**
     * @param \Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface $stateMachineFacade
     * @param \Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface $merchantOmsRepository
     */
    public function __construct(
        MerchantOmsToStateMachineFacadeInterface $stateMachineFacade,
        MerchantOmsRepositoryInterface $merchantOmsRepository
    ) {
        $this->stateMachineFacade = $stateMachineFacade;
        $this->merchantOmsRepository = $merchantOmsRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function expandMerchantOrderWithMerchantOmsData(MerchantOrderTransfer $merchantOrderTransfer): MerchantOrderTransfer
    {
        /** @var int[] $stateMachineItemStateIds */
        $stateMachineItemStateIds = $this->getStateMachineItemStateIds($merchantOrderTransfer);

        $stateMachineItemTransfers = $this->merchantOmsRepository->getStateMachineItemsByStateIds(
            $stateMachineItemStateIds
        );

        $manualEvents = $this->stateMachineFacade->getManualEventsForStateMachineItems($stateMachineItemTransfers);
        $merchantOrderTransfer->setManualEvents(array_unique(array_merge([], ...$manualEvents)));

        return $merchantOrderTransfer
            ->setItemStates($this->getUniqueItemStates($stateMachineItemTransfers))
            ->setProcess($this->findProcess($stateMachineItemTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItemTransfers
     *
     * @return string|null
     */
    protected function findProcess(array $stateMachineItemTransfers): ?string
    {
        if (!isset($stateMachineItemTransfers[0])) {
            return null;
        }

        return $stateMachineItemTransfers[0]->getProcessName();
    }

    /**
     * @phpstan-return array<array-key, int|null>
     *
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return int[]
     */
    protected function getStateMachineItemStateIds(MerchantOrderTransfer $merchantOrderTransfer): array
    {
        $stateMachineItemStateIds = [];
        foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            $stateMachineItemStateIds[] = $merchantOrderItemTransfer->getFkStateMachineItemState();
        }

        return $stateMachineItemStateIds;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItemTransfers
     *
     * @return string[]
     */
    protected function getUniqueItemStates(array $stateMachineItemTransfers): array
    {
        /** @var mixed[] $stateItems */
        $stateItems = [];

        foreach ($stateMachineItemTransfers as $stateMachineItemTransfer) {
            $stateItems[] = $stateMachineItemTransfer->getStateName();
        }

        return array_unique($stateItems);
    }
}
