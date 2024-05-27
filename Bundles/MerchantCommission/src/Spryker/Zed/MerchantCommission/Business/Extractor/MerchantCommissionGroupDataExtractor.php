<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Extractor;

use ArrayObject;

class MerchantCommissionGroupDataExtractor implements MerchantCommissionGroupDataExtractorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionGroupTransfer> $merchantCommissionGroupTransfers
     *
     * @return list<string>
     */
    public function extractMerchantCommissionGroupUuids(ArrayObject $merchantCommissionGroupTransfers): array
    {
        $merchantCommissionGroupUuids = [];
        foreach ($merchantCommissionGroupTransfers as $merchantCommissionGroupTransfer) {
            $merchantCommissionGroupUuids[$merchantCommissionGroupTransfer->getUuidOrFail()] = $merchantCommissionGroupTransfer->getUuidOrFail();
        }

        return array_values($merchantCommissionGroupUuids);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionGroupTransfer> $merchantCommissionGroupTransfers
     *
     * @return list<string>
     */
    public function extractUniqueMerchantCommissionGroupKeys(ArrayObject $merchantCommissionGroupTransfers): array
    {
        $merchantCommissionGroupKeys = [];
        foreach ($merchantCommissionGroupTransfers as $merchantCommissionGroupTransfer) {
            $merchantCommissionGroupKeys[$merchantCommissionGroupTransfer->getKeyOrFail()] = $merchantCommissionGroupTransfer->getKeyOrFail();
        }

        return array_values($merchantCommissionGroupKeys);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return list<string>
     */
    public function extractMerchantCommissionGroupUuidsFromMerchantCommissionTransfers(ArrayObject $merchantCommissionTransfers): array
    {
        $merchantCommissionGroupUuids = [];
        foreach ($merchantCommissionTransfers as $merchantCommissionTransfer) {
            $merchantCommissionGroupTransfer = $merchantCommissionTransfer->getMerchantCommissionGroupOrFail();
            $merchantCommissionGroupUuids[$merchantCommissionGroupTransfer->getUuidOrFail()] = $merchantCommissionGroupTransfer->getUuidOrFail();
        }

        return array_values($merchantCommissionGroupUuids);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return list<string>
     */
    public function extractMerchantCommissionGroupKeysFromMerchantCommissionTransfers(ArrayObject $merchantCommissionTransfers): array
    {
        $merchantCommissionGroupKeys = [];
        foreach ($merchantCommissionTransfers as $merchantCommissionTransfer) {
            $merchantCommissionGroupTransfer = $merchantCommissionTransfer->getMerchantCommissionGroupOrFail();
            $merchantCommissionGroupKeys[$merchantCommissionGroupTransfer->getKeyOrFail()] = $merchantCommissionGroupTransfer->getKeyOrFail();
        }

        return array_values($merchantCommissionGroupKeys);
    }
}
