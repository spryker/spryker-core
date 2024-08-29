<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderMatrix\Business\Indexer;

use Generated\Shared\Transfer\OrderMatrixCollectionTransfer;

class OrderMatrixIndexer implements OrderMatrixIndexerInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderMatrixCollectionTransfer $orderMatrixCollectionTransfer
     * @param array<string, array<string, array<int>>> $orderMatrix
     *
     * @return array<string, array<string, array<int>>>
     */
    public function getOrderMatrixIndexedByStateProcessAndDateRange(OrderMatrixCollectionTransfer $orderMatrixCollectionTransfer, array $orderMatrix): array
    {
        foreach ($orderMatrixCollectionTransfer->getOrderMatrices() as $orderMatrixTransfer) {
            $processName = $orderMatrixTransfer->getProcessName() ?? '';
            $stateName = $orderMatrixTransfer->getStateName() ?? '';
            $date = $orderMatrixTransfer->getDateWindow() ?? '';
            $itemsCount = $orderMatrixTransfer->getItemsCount();
            $idProcess = $orderMatrixTransfer->getIdProcess() ?? 0;
            $idState = $orderMatrixTransfer->getIdState() ?? 0;
            $processKey = sprintf('%s:%s', $idProcess, $processName);
            $stateKey = sprintf('%s:%s', $idState, $stateName);

            if (!isset($orderMatrix[$stateKey])) {
                $orderMatrix[$stateKey] = [];
            }

            if (!isset($orderMatrix[$stateKey][$processKey])) {
                $orderMatrix[$stateKey][$processKey] = [];
            }

            if (!isset($orderMatrix[$stateKey][$processKey][$date])) {
                $orderMatrix[$stateKey][$processKey][$date] = (int)$itemsCount;

                continue;
            }

            $orderMatrix[$stateKey][$processKey][$date] += (int)$itemsCount;
        }

        return $orderMatrix;
    }
}
