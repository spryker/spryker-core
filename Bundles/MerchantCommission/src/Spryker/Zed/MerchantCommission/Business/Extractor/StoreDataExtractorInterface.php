<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Extractor;

use ArrayObject;

interface StoreDataExtractorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return list<string>
     */
    public function extractStoreNamesFromStoreTransfers(ArrayObject $storeTransfers): array;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return list<int>
     */
    public function extractStoreIdsFromStoreTransfers(ArrayObject $storeTransfers): array;
}
