<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;

/**
 * Provides the ability to sort ProductOfferStorageCollection.
 */
interface ProductOfferStorageCollectionSorterPluginInterface
{
    /**
     * Specification:
     * - Sorts ProductOfferStorageCollection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer
     */
    public function sort(ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer): ProductOfferStorageCollectionTransfer;
}
