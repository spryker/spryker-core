<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Sorter;

use Generated\Shared\Transfer\MerchantCommissionTransfer;

class MerchantCommissionSorter implements MerchantCommissionSorterInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return list<\Generated\Shared\Transfer\MerchantCommissionTransfer>
     */
    public function sortMerchantCommissionsByPriority(array $merchantCommissionTransfers): array
    {
        $groupedMerchantCommissionTransfersGroupedByPriority = $this->getMerchantCommissionsGroupedByPriority(
            $merchantCommissionTransfers,
        );

        ksort($groupedMerchantCommissionTransfersGroupedByPriority);

        $sortedMerchantCommissionTransfers = [];
        foreach ($groupedMerchantCommissionTransfersGroupedByPriority as $merchantCommissionTransfers) {
            if (count($merchantCommissionTransfers) === 1) {
                $sortedMerchantCommissionTransfers[] = $merchantCommissionTransfers;

                continue;
            }

            $sortedMerchantCommissionTransfers[] = $this->sortMerchantCommissionsByCreatedAtDescending($merchantCommissionTransfers);
        }

        return array_merge(...$sortedMerchantCommissionTransfers);
    }

    /**
     * @param list<\Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return array<int, list<\Generated\Shared\Transfer\MerchantCommissionTransfer>>
     */
    protected function getMerchantCommissionsGroupedByPriority(array $merchantCommissionTransfers): array
    {
        $groupedMerchantCommissionTransfers = [];
        foreach ($merchantCommissionTransfers as $merchantCommissionTransfer) {
            $priority = $merchantCommissionTransfer->getPriorityOrFail();
            $groupedMerchantCommissionTransfers[$priority][] = $merchantCommissionTransfer;
        }

        return $groupedMerchantCommissionTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return list<\Generated\Shared\Transfer\MerchantCommissionTransfer>
     */
    protected function sortMerchantCommissionsByCreatedAtDescending(array $merchantCommissionTransfers): array
    {
        usort($merchantCommissionTransfers, function (MerchantCommissionTransfer $a, MerchantCommissionTransfer $b) {
            return strtotime($b->getCreatedAtOrFail()) - strtotime($a->getCreatedAtOrFail());
        });

        return $merchantCommissionTransfers;
    }
}
