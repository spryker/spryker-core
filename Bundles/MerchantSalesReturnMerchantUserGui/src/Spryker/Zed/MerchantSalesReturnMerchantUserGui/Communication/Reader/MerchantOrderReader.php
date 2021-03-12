<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Reader;

use ArrayObject;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToMerchantOmsFacadeInterface;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToMerchantSalesOrderFacadeInterface;

class MerchantOrderReader implements MerchantOrderReaderInterface
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
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
     */
    public function findMerchantSalesOrder(ReturnTransfer $returnTransfer): ?MerchantOrderTransfer
    {
        $merchantOrderCriteriaTransfer = (new MerchantOrderCriteriaTransfer())
            ->setMerchantOrderReference($returnTransfer->getMerchantSalesOrderReference())
            ->setWithItems(true);

        $merchantOrderTransfer = $this
            ->merchantSalesOrderFacade
            ->findMerchantOrder($merchantOrderCriteriaTransfer);

        if (!$merchantOrderTransfer) {
            return null;
        }

        return $merchantOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemTransfer[]
     */
    public function findMerchantOrderItems(ReturnTransfer $returnTransfer): array
    {
        $salesOrderItemIds = $this->extractSalesOrderItemIdsFromReturn($returnTransfer);

        $merchantOrderItemCriteriaTransfer = (new MerchantOrderItemCriteriaTransfer())
            ->setOrderItemIds($salesOrderItemIds);

        $merchantOrderItemTransfers = $this->merchantSalesOrderFacade
            ->getMerchantOrderItemCollection($merchantOrderItemCriteriaTransfer);

        $merchantOrderItemTransfers = $this->expandMerchantOrderItemsWithManualEvents($merchantOrderItemTransfers);
        $merchantOrderItemTransfers = $this->expandMerchantOrderItemsStateHistory($merchantOrderItemTransfers);
        $merchantOrderItemTransfers = $this->createMerchantOrderItemsIndexMapping($merchantOrderItemTransfers);

        return $merchantOrderItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return int[]
     */
    protected function extractSalesOrderItemIdsFromReturn(ReturnTransfer $returnTransfer): array
    {
        $salesOrderItemIds = [];

        foreach ($returnTransfer->getReturnItems() as $returnItemTransfer) {
            $salesOrderItemIds[] = $returnItemTransfer->getOrderItem()->getIdSalesOrderItem();
        }

        return $salesOrderItemIds;
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
            $merchantOrderItemIds[] = $merchantOrderItemTransfer->getIdMerchantOrderItem();
        }

        $stateMachineItemTransfers = $this->merchantOmsFacade
            ->getMerchantOrderItemsStateHistory($merchantOrderItemIds);

        foreach ($merchantOrderItemTransfers->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            if (isset($stateMachineItemTransfers[$merchantOrderItemTransfer->getIdMerchantOrder()])) {
                $stateHistory = $stateMachineItemTransfers[$merchantOrderItemTransfer->getIdMerchantOrder()];
                $merchantOrderItemTransfer->setStateHistory(new ArrayObject($stateHistory));
                $merchantOrderItemTransfer->setState(reset($stateHistory));
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
