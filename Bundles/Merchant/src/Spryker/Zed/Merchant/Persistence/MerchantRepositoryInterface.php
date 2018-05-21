<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Persistence;

use Generated\Shared\Transfer\MerchantTransfer;

interface MerchantRepositoryInterface
{
    /**
     * Specification:
     *  - Retrieves a merchant by merchant ID.
     *
     * @api
     *
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function getMerchantById(int $idMerchant): MerchantTransfer;
}
