<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductStorage;

use Generated\Shared\Transfer\MerchantProductStorageTransfer;

interface MerchantProductStorageClientInterface
{
    /**
     * Specification:
     * - Gets merchant product abstract relation data by provided idProductAbstract.
     * - Returns `MerchantProductStorageTransfer`.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\MerchantProductStorageTransfer|null
     */
    public function findOne(int $idProductAbstract): ?MerchantProductStorageTransfer;
}
