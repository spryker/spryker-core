<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailability\Business;

use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer;

interface ProductOfferAvailabilityFacadeInterface
{
    /**
     * Specification:
     * - Returns true if product offer is available in requested quantity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
     *
     * @return bool
     */
    public function isProductSellableForRequest(ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer): bool;

    /**
     * Specification:
     * - Finds availability for product offer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailabilityForRequest(ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer): ?ProductConcreteAvailabilityTransfer;
}
