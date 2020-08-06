<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Persistence;

use Generated\Shared\Transfer\MerchantStorageTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface MerchantStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer
     */
    public function saveMerchantStorage(MerchantStorageTransfer $merchantStorageTransfer, StoreTransfer $storeTransfer): MerchantStorageTransfer;

    /**
     * @param int $idMerchant
     * @param string $store
     *
     * @return void
     */
    public function deleteMerchantStorage(int $idMerchant, string $store): void;
}
