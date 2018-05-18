<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Persistence;

use Generated\Shared\Transfer\SpyMerchantEntityTransfer;

interface MerchantRepositoryInterface
{
    /**
     * @api
     *
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\SpyMerchantEntityTransfer|null
     */
    public function getMerchantById(int $idMerchant): ?SpyMerchantEntityTransfer;
}
