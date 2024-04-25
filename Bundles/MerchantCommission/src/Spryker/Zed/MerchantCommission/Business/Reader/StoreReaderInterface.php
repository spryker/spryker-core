<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Reader;

use Generated\Shared\Transfer\StoreCollectionTransfer;

interface StoreReaderInterface
{
    /**
     * @param list<int> $storeIds
     *
     * @return \Generated\Shared\Transfer\StoreCollectionTransfer
     */
    public function getStoreCollectionByStoreIds(array $storeIds): StoreCollectionTransfer;

    /**
     * @param list<string> $storeNames
     *
     * @return \Generated\Shared\Transfer\StoreCollectionTransfer
     */
    public function getStoreCollectionByStoreNames(array $storeNames): StoreCollectionTransfer;
}
