<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointAvailability\Business;

use Generated\Shared\Transfer\SellableItemsRequestTransfer;
use Generated\Shared\Transfer\SellableItemsResponseTransfer;

interface ProductOfferServicePointAvailabilityFacadeInterface
{
    /**
     * Specification:
     * - Filters out sellable item requests without product offer reference or service point.
     * - Checks if sellable item request has valid service point for the provided product offer reference.
     * - Returns `SellableItemsResponseTransfer` filled with not valid sellable item responses.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SellableItemsRequestTransfer $sellableItemsRequestTransfer
     * @param \Generated\Shared\Transfer\SellableItemsResponseTransfer $sellableItemsResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemsResponseTransfer
     */
    public function getItemsAvailabilityForStore(
        SellableItemsRequestTransfer $sellableItemsRequestTransfer,
        SellableItemsResponseTransfer $sellableItemsResponseTransfer
    ): SellableItemsResponseTransfer;
}
