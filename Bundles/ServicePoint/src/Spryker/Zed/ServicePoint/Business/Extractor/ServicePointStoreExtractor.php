<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Extractor;

use Generated\Shared\Transfer\StoreRelationTransfer;

class ServicePointStoreExtractor implements ServicePointStoreExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return list<string>
     */
    public function extractStoreNamesFromStoreRelationTransfer(StoreRelationTransfer $storeRelationTransfer): array
    {
        $storeNames = [];

        foreach ($storeRelationTransfer->getStores() as $storeTransfer) {
            $storeNames[] = $storeTransfer->getNameOrFail();
        }

        return $storeNames;
    }

    /**
     * @param list<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return list<int>
     */
    public function extractStoreIdsFromStoreTransfers(array $storeTransfers): array
    {
        $storeIds = [];

        foreach ($storeTransfers as $storeTransfer) {
            $storeIds[] = $storeTransfer->getIdStoreOrFail();
        }

        return $storeIds;
    }
}
