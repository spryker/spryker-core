<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductOfferStorage\Plugin\MerchantProductOfferStorage;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageCollectionSorterPluginInterface;

/**
 * @deprecated Use {@link \Spryker\Client\PriceProductOfferStorage\Plugin\ProductOfferStorage\LowestPriceProductOfferStorageCollectionSorterPlugin} instead.
 *
 * @method \Spryker\Client\PriceProductOfferStorage\PriceProductOfferStorageFactory getFactory()
 */
class LowestPriceProductOfferStorageCollectionSorterPlugin extends AbstractPlugin implements ProductOfferStorageCollectionSorterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Sorts ProductOfferStorageCollection by price from low to high.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer
     */
    public function sort(ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer): ProductOfferStorageCollectionTransfer
    {
        return $this->getFactory()->createPriceProductOfferStorageSorter()->sort($productOfferStorageCollectionTransfer);
    }
}
