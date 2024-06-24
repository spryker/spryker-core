<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Merger;

class MerchantCommissionMerger implements MerchantCommissionMergerInterface
{
    /**
     * @param array<string, list<\Generated\Shared\Transfer\CollectedMerchantCommissionTransfer>> $collectedMerchantCommissionTransfersGroupedByGroupKey
     *
     * @return array<string, \Generated\Shared\Transfer\CollectedMerchantCommissionTransfer>
     */
    public function mergeCollectedMerchantCommissions(array $collectedMerchantCommissionTransfersGroupedByGroupKey): array
    {
        $mergedCollectedMerchantCommissionTransfers = [];
        foreach ($collectedMerchantCommissionTransfersGroupedByGroupKey as $collectedMerchantCommissionTransfers) {
            $mergedCollectedMerchantCommissionTransfers = $this->mergeCollectedMerchantCommissionTransfersForMerchantCommissionGroup(
                $collectedMerchantCommissionTransfers,
                $mergedCollectedMerchantCommissionTransfers,
            );
        }

        return $mergedCollectedMerchantCommissionTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\CollectedMerchantCommissionTransfer> $collectedMerchantCommissionTransfers
     * @param array<string, \Generated\Shared\Transfer\CollectedMerchantCommissionTransfer> $mergedCollectedMerchantCommissionTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\CollectedMerchantCommissionTransfer>
     */
    protected function mergeCollectedMerchantCommissionTransfersForMerchantCommissionGroup(
        array $collectedMerchantCommissionTransfers,
        array $mergedCollectedMerchantCommissionTransfers
    ): array {
        foreach ($collectedMerchantCommissionTransfers as $collectedMerchantCommissionTransfer) {
            $merchantCommissionUuid = $collectedMerchantCommissionTransfer->getMerchantCommissionOrFail()->getUuidOrFail();
            if (!isset($mergedCollectedMerchantCommissionTransfers[$merchantCommissionUuid])) {
                $mergedCollectedMerchantCommissionTransfers[$merchantCommissionUuid] = $collectedMerchantCommissionTransfer;

                continue;
            }

            $mergedCollectedMerchantCommissionTransfers[$merchantCommissionUuid]->addCommissionableItem(
                $collectedMerchantCommissionTransfer->getCommissionableItems()->getIterator()->current(),
            );
        }

        return $mergedCollectedMerchantCommissionTransfers;
    }
}
