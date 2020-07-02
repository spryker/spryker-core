<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Persistence;

use Generated\Shared\Transfer\MerchantProductTransfer;

interface MerchantProductStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProductTransfer $merchantProductTransfer
     *
     * @return void
     */
    public function saveMerchantProductStorage(MerchantProductTransfer $merchantProductTransfer): void;

    /**
     * @param int[] $idProductAbstracts
     *
     * @return void
     */
    public function deleteMerchantProductStorageEntitiesByIdProductAbstracts(array $idProductAbstracts): void;
}
