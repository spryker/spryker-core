<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointStorage\Plugin\ProductOfferStorage;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductOfferServicePointStorage\ProductOfferServicePointStorageFactory getFactory()
 * @method \Spryker\Client\ProductOfferServicePointStorage\ProductOfferServicePointStorageClientInterface getClient()
 */
class ServiceProductOfferStorageExpanderPlugin extends AbstractPlugin implements ProductOfferStorageExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ProductOfferStorageCollectionTransfer.productOffers.productOfferReference` to be set.
     * - Expands product offer storage transfers with services from storage.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer
     */
    public function expand(ProductOfferStorageTransfer $productOfferStorageTransfer): ProductOfferStorageTransfer
    {
        $productOfferStorageCollectionTransfer = (new ProductOfferStorageCollectionTransfer())
            ->addProductOffer($productOfferStorageTransfer);

        $productOfferStorageCollectionTransfer = $this->getClient()->expandProductOfferStorageCollectionWithServices($productOfferStorageCollectionTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferStorageTransfer> $productOfferStorageTransfers */
        $productOfferStorageTransfers = $productOfferStorageCollectionTransfer->getProductOffers();

        return $productOfferStorageTransfers->getIterator()->current();
    }
}
