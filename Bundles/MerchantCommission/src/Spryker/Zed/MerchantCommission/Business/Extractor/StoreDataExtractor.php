<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Extractor;

use ArrayObject;

class StoreDataExtractor implements StoreDataExtractorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return list<string>
     */
    public function extractStoreNamesFromStoreTransfers(ArrayObject $storeTransfers): array
    {
        $storeNames = [];
        foreach ($storeTransfers as $storeTransfer) {
            $storeNames[] = $storeTransfer->getNameOrFail();
        }

        return $storeNames;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return list<int>
     */
    public function extractStoreIdsFromStoreTransfers(ArrayObject $storeTransfers): array
    {
        $storeIds = [];
        foreach ($storeTransfers as $storeTransfer) {
            $storeIds[] = $storeTransfer->getIdStoreOrFail();
        }

        return $storeIds;
    }
}
