<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Plugin;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageCollectionSorterPluginInterface;

/**
 * @method \Spryker\Client\PriceProductStorage\PriceProductStorageFactory getFactory()
 */
class LowestPriceProductOfferStorageCollectionSorterPlugin extends AbstractPlugin implements ProductOfferStorageCollectionSorterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Sorts ProductOfferStorageCollection by price increase.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer
     */
    public function sort(ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer): ProductOfferStorageCollectionTransfer
    {
        return $this->getFactory()->createPriceProductStorageSorter()->sort($productOfferStorageCollectionTransfer);
    }
}
