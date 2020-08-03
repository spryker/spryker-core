<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business\Expander;

use Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer;
use Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface;
use Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface;

class MerchantOrderItemsExpander implements MerchantOrderItemsExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface
     */
    protected $merchantOmsRepository;

    /**
     * @var \Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface
     */
    protected $stateMachineFacade;

    /**
     * @param \Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface $merchantOmsRepository
     * @param \Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface $stateMachineFacade
     */
    public function __construct(
        MerchantOmsRepositoryInterface $merchantOmsRepository,
        MerchantOmsToStateMachineFacadeInterface $stateMachineFacade
    ) {
        $this->merchantOmsRepository = $merchantOmsRepository;
        $this->stateMachineFacade = $stateMachineFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer $merchantOrderItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer
     */
    public function expandMerchantOrderItemsWithManualEvents(
        MerchantOrderItemCollectionTransfer $merchantOrderItemCollectionTransfer
    ): MerchantOrderItemCollectionTransfer {
        $stateMachineItemStateIds = [];
        $merchantOrderItemTransfers = $merchantOrderItemCollectionTransfer->getMerchantOrderItems();

        if (!$merchantOrderItemTransfers->count()) {
            return $merchantOrderItemCollectionTransfer;
        }

        foreach ($merchantOrderItemTransfers as $merchantOrderItemTransfer) {
            $stateMachineItemStateIds[] = $merchantOrderItemTransfer
                ->requireFkStateMachineItemState()
                ->getFkStateMachineItemState();
        }

        $stateMachineItemTransfers = $this->merchantOmsRepository->getStateMachineItemsByStateIds(
            array_unique($stateMachineItemStateIds)
        );

        foreach ($merchantOrderItemTransfers as $merchantOrderItemTransfer) {
            foreach ($stateMachineItemTransfers as $stateMachineItemTransfer) {
                if ($merchantOrderItemTransfer->getIdMerchantOrderItem() === $stateMachineItemTransfer->getIdentifier()) {
                    $merchantOrderItemTransfer->setManualEvents(
                        $this->stateMachineFacade->getManualEventsForStateMachineItem($stateMachineItemTransfer)
                    );
                }
            }
        }

        $merchantOrderItemCollectionTransfer->setMerchantOrderItems($merchantOrderItemTransfers);

        return $merchantOrderItemCollectionTransfer;
    }
}
