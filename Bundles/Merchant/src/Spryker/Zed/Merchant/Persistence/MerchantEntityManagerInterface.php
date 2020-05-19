<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Persistence;

use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface MerchantEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function saveMerchant(MerchantTransfer $merchantTransfer): MerchantTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function createMerchantStore(MerchantTransfer $merchantTransfer, int $idStore): StoreTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param int $idStore
     *
     * @return void
     */
    public function deleteMerchantStore(MerchantTransfer $merchantTransfer, int $idStore): void;
}
