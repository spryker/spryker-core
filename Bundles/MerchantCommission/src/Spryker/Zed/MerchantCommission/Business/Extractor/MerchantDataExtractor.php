<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Extractor;

use ArrayObject;

class MerchantDataExtractor implements MerchantDataExtractorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantTransfer> $merchantTransfers
     *
     * @return list<string>
     */
    public function extractMerchantReferencesFromMerchantTransfers(ArrayObject $merchantTransfers): array
    {
        $merchantReferences = [];
        foreach ($merchantTransfers as $merchantTransfer) {
            $merchantReferences[] = $merchantTransfer->getMerchantReferenceOrFail();
        }

        return $merchantReferences;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantTransfer> $merchantTransfers
     *
     * @return list<int>
     */
    public function extractMerchantIdsFromMerchantTransfers(ArrayObject $merchantTransfers): array
    {
        $merchantIds = [];
        foreach ($merchantTransfers as $merchantTransfer) {
            $merchantIds[] = $merchantTransfer->getIdMerchantOrFail();
        }

        return $merchantIds;
    }
}
