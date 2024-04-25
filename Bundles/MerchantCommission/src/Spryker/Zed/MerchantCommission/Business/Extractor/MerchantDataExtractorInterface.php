<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Extractor;

use ArrayObject;

interface MerchantDataExtractorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantTransfer> $merchantTransfers
     *
     * @return list<string>
     */
    public function extractMerchantReferencesFromMerchantTransfers(ArrayObject $merchantTransfers): array;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantTransfer> $merchantTransfers
     *
     * @return list<int>
     */
    public function extractMerchantIdsFromMerchantTransfers(ArrayObject $merchantTransfers): array;
}
