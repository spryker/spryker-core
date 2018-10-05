<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Persistence;

use Generated\Shared\Transfer\MerchantTransfer;

interface MerchantEntityManagerInterface
{
    /**
     * Specification:
     * - Finds a merchant by merchant ID.
     * - Deletes the merchant.
     *
     * @param int $idMerchant
     *
     * @return void
     */
    public function deleteMerchantById(int $idMerchant): void;

    /**
     * Specification:
     * - Creates a merchant.
     * - Finds a merchant by MerchantTransfer::idMerchant in the transfer.
     * - Updates fields in a merchant entity.
     * - Persists the entity to DB.
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function saveMerchant(MerchantTransfer $merchantTransfer): MerchantTransfer;
}
