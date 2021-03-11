<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Reader;

use Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToMerchantOmsFacadeInterface;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToMerchantSalesOrderFacadeInterface;

class MerchantOrderItemReader implements MerchantOrderItemReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToMerchantSalesOrderFacadeInterface
     */
    protected $merchantSalesOrderFacade;

    /**
     * @var \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToMerchantOmsFacadeInterface
     */
    protected $merchantOmsFacade;

    /**
     * @param \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToMerchantSalesOrderFacadeInterface $merchantSalesOrderFacade
     * @param \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToMerchantOmsFacadeInterface $merchantOmsFacade
     */
    public function __construct(
        MerchantSalesReturnMerchantUserGuiToMerchantSalesOrderFacadeInterface $merchantSalesOrderFacade,
        MerchantSalesReturnMerchantUserGuiToMerchantOmsFacadeInterface $merchantOmsFacade
    ) {
        $this->merchantSalesOrderFacade = $merchantSalesOrderFacade;
        $this->merchantOmsFacade = $merchantOmsFacade;
    }

    /**
     * @param int[] $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemTransfer[]
     */
    public function findMerchantOrderItems(array $salesOrderItemIds): array
    {
        $merchantOrderItemCriteriaTransfer = new MerchantOrderItemCriteriaTransfer();
        $merchantOrderItemCriteriaTransfer->setOrderItemIds($salesOrderItemIds);

        $merchantOrderItemTransfers = $this->merchantSalesOrderFacade
            ->getMerchantOrderItemCollection($merchantOrderItemCriteriaTransfer);

        $merchantOrderItemTransfers = $this->expandMerchantOrderItemsWithManualEvents($merchantOrderItemTransfers);
        $merchantOrderItemTransfers = $this->expandMerchantOrderItemsStateHistory($merchantOrderItemTransfers);
        $merchantOrderItemTransfers = $this->createMerchantOrderItemsIndexMapping($merchantOrderItemTransfers);

        return $merchantOrderItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer $merchantOrderItemTransfers
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer
     */
    protected function expandMerchantOrderItemsWithManualEvents(
        MerchantOrderItemCollectionTransfer $merchantOrderItemTransfers
    ): MerchantOrderItemCollectionTransfer {
        return $this->merchantOmsFacade
            ->expandMerchantOrderItemsWithManualEvents($merchantOrderItemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer $merchantOrderItemTransfers
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer
     */
    protected function expandMerchantOrderItemsStateHistory(
        MerchantOrderItemCollectionTransfer $merchantOrderItemTransfers
    ): MerchantOrderItemCollectionTransfer {
        $merchantOrderItemIds = [];

        foreach ($merchantOrderItemTransfers->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            $merchantOderItemIds[] = $merchantOrderItemTransfer->getIdMerchantOrderItem();
        }

        $stateMachineItemTransfers = $this->merchantOmsFacade
            ->getMerchantOrderItemsStateHistory($merchantOrderItemIds);

        foreach ($merchantOrderItemTransfers->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            if (isset($stateMachineItemTransfers[$merchantOrderItemTransfer->getIdMerchantOrder()])) {
                $stateHistory = $stateMachineItemTransfers[$merchantOrderItemTransfer->getIdMerchantOrder()];
                $merchantOrderItemTransfer->setStateHistory($stateHistory);
            }
        }

        return $merchantOrderItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer $merchantOrderItemTransfers
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemTransfer[]
     */
    protected function createMerchantOrderItemsIndexMapping(
        MerchantOrderItemCollectionTransfer $merchantOrderItemTransfers
    ): array {
        $indexedMerchantOrderItemTransfers = [];
        foreach ($merchantOrderItemTransfers->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            $indexedMerchantOrderItemTransfers[$merchantOrderItemTransfer->getIdOrderItem()] = $merchantOrderItemTransfer;
        }

        return $indexedMerchantOrderItemTransfers;
    }
}
