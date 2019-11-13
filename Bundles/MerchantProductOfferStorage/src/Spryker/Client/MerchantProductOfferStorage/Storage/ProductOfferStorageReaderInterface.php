<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorage\Storage;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;

interface ProductOfferStorageReaderInterface
{
    /**
     * @param string $concreteSku
     *
     * @return string[]
     */
    public function getProductOfferReferences(string $concreteSku): array;

    /**
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer
     */
    public function getProductOfferStorageCollection(string $concreteSku): ProductOfferStorageCollectionTransfer;
}
