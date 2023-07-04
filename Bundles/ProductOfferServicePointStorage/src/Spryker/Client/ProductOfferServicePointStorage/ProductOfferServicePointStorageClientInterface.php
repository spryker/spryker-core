<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointStorage;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;

interface ProductOfferServicePointStorageClientInterface
{
    /**
     * Specification:
     * - Requires `ProductOfferStorageCollectionTransfer.productOffers.productOfferReference` to be set.
     * - Expands product offer storage transfers with services from storage.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer
     */
    public function expandProductOfferStorageCollectionWithServices(
        ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
    ): ProductOfferStorageCollectionTransfer;
}
