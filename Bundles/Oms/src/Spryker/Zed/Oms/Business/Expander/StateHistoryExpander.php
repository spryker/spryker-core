<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Expander;

use ArrayObject;
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
        $mappedItemStateTransfers = $this->omsRepository->getItemHistoryStatesByOrderItemIds($salesOrderItemIds);

        foreach ($itemTransfers as $itemTransfer) {
            $itemTransfer->setStateHistory(
                new ArrayObject($mappedItemStateTransfers[$itemTransfer->getIdSalesOrderItem()] ?? [])
            );
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
}
