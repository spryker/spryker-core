<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Oms\Persistence\OmsRepositoryInterface;

class StateHistoryExpander implements StateHistoryExpanderInterface
{
    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface
     */
    protected $omsRepository;

    /**
     * @param \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface $omsRepository
     */
    public function __construct(OmsRepositoryInterface $omsRepository)
    {
        $this->omsRepository = $omsRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithStateHistory(array $itemTransfers): array
    {
        $salesOrderItemIds = $this->extractSalesOrderItemIds($itemTransfers);

        $itemStateTransfers = $this->omsRepository->getItemHistoryStatesByOrderItemIds($salesOrderItemIds);
        $mappedItemStateTransfers = $this->mapItemStatesByIdSalesOrderItem($itemStateTransfers);

        foreach ($itemTransfers as $itemTransfer) {
            $itemTransfer->setStateHistory(
                new ArrayObject($mappedItemStateTransfers[$itemTransfer->getIdSalesOrderItem()] ?? [])
            );

            $this->updateLatestOrderItemState($itemTransfer);
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function updateLatestOrderItemState(ItemTransfer $itemTransfer): ItemTransfer
    {
        $latestOrderItemState = $itemTransfer->getStateHistory()->getIterator()->current();

        if ($latestOrderItemState && $itemTransfer->getState()) {
            $itemTransfer->getState()->setCreatedAt($latestOrderItemState->getCreatedAt());
        }

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemStateTransfer[] $itemStateTransfers
     *
     * @return \Generated\Shared\Transfer\ItemStateTransfer[][]
     */
    protected function mapItemStatesByIdSalesOrderItem(array $itemStateTransfers): array
    {
        $mappedItemStateTransfers = [];

        foreach ($itemStateTransfers as $itemStateTransfer) {
            $mappedItemStateTransfers[$itemStateTransfer->getIdSalesOrderItem()][] = $itemStateTransfer;
        }

        return $mappedItemStateTransfers;
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
}
