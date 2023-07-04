<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorage\Persistence;

use Generated\Shared\Transfer\ProductOfferServiceStorageTransfer;

interface ProductOfferServicePointStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceStorageTransfer $productOfferServiceStorageTransfer
     * @param string $storeName
     *
     * @return void
     */
    public function saveProductOfferServiceForStore(
        ProductOfferServiceStorageTransfer $productOfferServiceStorageTransfer,
        string $storeName
    ): void;

    /**
     * @param list<string> $productOfferReferences
     * @param string|null $storeName
     *
     * @return void
     */
    public function deleteProductOfferServiceStorageByProductOfferReferences(array $productOfferReferences, ?string $storeName = null): void;
}
