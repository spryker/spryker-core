<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferAvailabilityStorage;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageFactory getFactory()
 */
class ProductOfferAvailabilityStorageClient extends AbstractClient implements ProductOfferAvailabilityStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isProductOfferAvailableForStore(ProductOfferTransfer $productOfferTransfer, StoreTransfer $storeTransfer): bool
    {
        return $this->getFactory()
            ->createProductOfferAvailabilityStorageReader()
            ->isProductOfferAvailableForStore($productOfferTransfer, $storeTransfer);
    }
}
