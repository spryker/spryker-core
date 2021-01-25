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
     * - Calculates product offer availability by product offer stock and concrete product reserved amount.
     * - Expects ProductOfferAvailabilityFacadeInterface.sku to be provided.
     * - Expects ProductOfferAvailabilityFacadeInterface.productOfferReference to be provided.
     * - Expects ProductOfferAvailabilityFacadeInterface.store to be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailabilityForRequest(
        ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
    ): ?ProductConcreteAvailabilityTransfer;
}
