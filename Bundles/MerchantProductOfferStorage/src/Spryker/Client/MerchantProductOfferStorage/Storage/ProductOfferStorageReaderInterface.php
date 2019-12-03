<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage\Storage;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;

interface ProductOfferStorageReaderInterface
{
    /**
     * @param string $productSku
     *
     * @return string[]
     */
    public function getProductOfferReferences(string $productSku): array;

    /**
     * @param string $productSku
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer
     */
    public function getProductOfferStorageCollection(string $productSku): ProductOfferStorageCollectionTransfer;
}
