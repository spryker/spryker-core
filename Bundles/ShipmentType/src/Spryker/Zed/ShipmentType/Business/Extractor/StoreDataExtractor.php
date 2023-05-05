<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business\Extractor;

class StoreDataExtractor implements StoreDataExtractorInterface
{
    /**
     * @param iterable<array-key, \Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return list<string>
     */
    public function extractStoreNamesFromStoreTransfers(iterable $storeTransfers): array
    {
        $storeNames = [];
        foreach ($storeTransfers as $storeTransfer) {
            $storeNames[] = $storeTransfer->getNameOrFail();
        }

        return $storeNames;
    }

    /**
     * @param iterable<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return list<int>
     */
    public function extractStoreIdsFromStoreTransfers(iterable $storeTransfers): array
    {
        $storeIds = [];
        foreach ($storeTransfers as $storeTransfer) {
            $storeIds[] = $storeTransfer->getIdStoreOrFail();
        }

        return $storeIds;
    }
}
