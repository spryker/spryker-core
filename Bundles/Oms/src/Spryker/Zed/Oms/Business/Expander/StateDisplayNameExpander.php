<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Expander;

use Generated\Shared\Transfer\ItemStateTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Spryker\Zed\Oms\Business\OrderStateMachine\FinderInterface;
use Spryker\Zed\Oms\Persistence\OmsRepositoryInterface;

class StateDisplayNameExpander implements StateDisplayNameExpanderInterface
{
    /**
     * @var \Spryker\Zed\Oms\Business\OrderStateMachine\FinderInterface
     */
    protected $finder;

    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface
     */
    protected $omsRepository;

    /**
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\FinderInterface $finder
     * @param \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface $omsRepository
     */
    public function __construct(FinderInterface $finder, OmsRepositoryInterface $omsRepository)
    {
        $this->finder = $finder;
        $this->omsRepository = $omsRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithStateDisplayName(array $itemTransfers): array
    {
        $salesOrderItemIds = $this->extractSalesOrderItemIds($itemTransfers);
        $orderItemFilterTransfer = $this->createOrderItemFilterTransfer($salesOrderItemIds);
        $orderItemTransfers = $this->omsRepository->getOrderItems($orderItemFilterTransfer);
        $mappedItemTransfersByIdSalesOrderItem = $this->mapItemsByIdSalesOrderItem($orderItemTransfers);

        foreach ($itemTransfers as $itemTransfer) {
            $mappedItemTransfer = $mappedItemTransfersByIdSalesOrderItem[$itemTransfer->getIdSalesOrderItem()] ?? null;
            if (!$mappedItemTransfer) {
                continue;
            }

            $displayName = $this->finder->getItemStateDisplayName($mappedItemTransfer);
            if (!$displayName) {
                continue;
            }

            $this->setItemStateDisplayName($itemTransfer, $displayName);
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return int[]
     */
    protected function extractSalesOrderItemIds(array $itemTransfers): array
    {
        $salesOrderItemIds = [];

        foreach ($itemTransfers as $itemTransfer) {
            $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItem();
        }

        return $salesOrderItemIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function mapItemsByIdSalesOrderItem(array $itemTransfers): array
    {
        $mappedItemTransfers = [];

        foreach ($itemTransfers as $itemTransfer) {
            $mappedItemTransfers[$itemTransfer->getIdSalesOrderItem()] = $itemTransfer;
        }

        return $mappedItemTransfers;
    }

    /**
     * @param int[] $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\OrderItemFilterTransfer
     */
    protected function createOrderItemFilterTransfer(array $salesOrderItemIds): OrderItemFilterTransfer
    {
        return (new OrderItemFilterTransfer())->setSalesOrderItemIds($salesOrderItemIds);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $displayName
     *
     * @return void
     */
    protected function setItemStateDisplayName(ItemTransfer $itemTransfer, string $displayName): void
    {
        if (!$itemTransfer->getState()) {
            $itemTransfer->setState((new ItemStateTransfer())->setDisplayName($displayName));
        }

        $itemTransfer->getState()->setDisplayName($displayName);
    }
}
