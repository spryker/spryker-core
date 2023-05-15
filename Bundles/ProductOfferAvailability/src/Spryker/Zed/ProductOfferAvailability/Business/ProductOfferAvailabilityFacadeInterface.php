<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailability\Business;

use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer;

interface ProductOfferAvailabilityFacadeInterface
{
    /**
     * Specification:
     * - Calculates product offer availability by product offer store, product offer stock and concrete product reserved amount.
     * - Expects `ProductOfferAvailabilityRequestTransfer.sku` to be provided.
     * - Expects `ProductOfferAvailabilityRequestTransfer.productOfferReference` to be provided.
     * - Expects `ProductOfferAvailabilityRequestTransfer.store.idStore` to be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailability(
        ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
    ): ?ProductConcreteAvailabilityTransfer;
}
