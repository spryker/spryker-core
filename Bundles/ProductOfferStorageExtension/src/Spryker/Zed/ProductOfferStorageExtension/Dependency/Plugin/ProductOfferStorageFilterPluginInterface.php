<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStorageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;

/**
 * Provides ability to filter product offer collection by provided criteria.
 */
interface ProductOfferStorageFilterPluginInterface
{
    /**
     * Specification:
     * - Filters `ProductOfferCollection` transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function filterProductOfferStorages(
        ProductOfferCollectionTransfer $productOfferCollectionTransfer
    ): ProductOfferCollectionTransfer;
}
