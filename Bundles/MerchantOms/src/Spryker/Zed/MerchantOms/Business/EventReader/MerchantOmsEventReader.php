<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business\EventReader;

use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToMerchantSalesOrderFacadeInterface;
use Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface;
use Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface;

class MerchantOmsEventReader implements MerchantOmsEventReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface
     */
    protected $stateMachineFacade;

    /**
     * @var \Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToMerchantSalesOrderFacadeInterface
     */
    protected $merchantSalesOrderFacade;

    /**
     * @var \Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface
     */
    protected $merchantOmsRepository;

    /**
     * @param \Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface $stateMachineFacade
     * @param \Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToMerchantSalesOrderFacadeInterface $merchantSalesOrderFacade
     * @param \Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface $merchantOmsRepository
     */
    public function __construct(
        MerchantOmsToStateMachineFacadeInterface $stateMachineFacade,
        MerchantOmsToMerchantSalesOrderFacadeInterface $merchantSalesOrderFacade,
        MerchantOmsRepositoryInterface $merchantOmsRepository
    ) {
        $this->stateMachineFacade = $stateMachineFacade;
        $this->merchantSalesOrderFacade = $merchantSalesOrderFacade;
        $this->merchantOmsRepository = $merchantOmsRepository;
    }

    /**
     * @param int $idMerchantOrder
     *
     * @return string[]
     */
    public function getManualEventsByIdMerchantOrder(int $idMerchantOrder): array
    {
        $merchantOrderTransfer = $this->merchantSalesOrderFacade->findMerchantOrder(
            (new MerchantOrderCriteriaTransfer())
                ->setIdMerchantOrder($idMerchantOrder)
                ->setWithItems(true)
        );

        $stateMachineItemStateIds = $this->getStateMachineItemStateIds($merchantOrderTransfer);

        $manualEvents = $this->stateMachineFacade->getManualEventsForStateMachineItems(
            $this->merchantOmsRepository->getStateMachineItemsByStateIds($stateMachineItemStateIds)
        );

        return array_unique(array_merge([], ...$manualEvents));
    }

    /**
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
}
