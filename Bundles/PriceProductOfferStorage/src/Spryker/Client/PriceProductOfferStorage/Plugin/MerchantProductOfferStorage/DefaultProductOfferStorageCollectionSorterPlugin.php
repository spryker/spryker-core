<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductOfferStorage\Plugin\MerchantProductOfferStorage;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageCollectionSorterPluginInterface;

class DefaultProductOfferStorageCollectionSorterPlugin implements ProductOfferStorageCollectionSorterPluginInterface
{
    /**
     * Specification:
     * - Default plugin to sort ProductOfferStorageCollection. No sorting actually occurs
     *
     * @inheritDoc
     */
    public function sort(ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer): ProductOfferStorageCollectionTransfer
    {
        return $productOfferStorageCollectionTransfer;
    }
}
