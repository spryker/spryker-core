<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointStorage\Reader;

use Generated\Shared\Transfer\ProductOfferServiceStorageCollectionTransfer;

interface ProductOfferServiceStorageReaderInterface
{
    /**
     * @param list<string> $productOfferReferences
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceStorageCollectionTransfer
     */
    public function getProductOfferServiceStorageCollectionByProductOfferReferences(
        array $productOfferReferences
    ): ProductOfferServiceStorageCollectionTransfer;
}
