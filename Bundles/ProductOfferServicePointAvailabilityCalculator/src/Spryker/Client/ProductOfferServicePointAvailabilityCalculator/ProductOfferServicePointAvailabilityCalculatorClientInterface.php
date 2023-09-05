<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityCalculator;

use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer;

interface ProductOfferServicePointAvailabilityCalculatorClientInterface
{
    /**
     * Specification:
     * - Requires `ProductOfferServicePointAvailabilityCriteriaTransfer.productOfferServicePointAvailabilityConditions` to be set.
     * - Requires `ProductOfferServicePointAvailabilityConditionsTransfer.productOfferServicePointAvailabilityRequestItems` to be set.
     * - Requires `ProductOfferServicePointAvailabilityRequestItemTransfer.productConcreteSku` to be set.
     * - Requires `ProductOfferServicePointAvailabilityRequestItemTransfer.productOfferReference` to be set.
     * - Requires `ProductOfferServicePointAvailabilityRequestItemTransfer.quantity` to be set.
     * - If `ProductOfferServicePointAvailabilityConditionsTransfer.storeName` is not set, uses current store name.
     * - Finds applicable strategy by executing {@link \Spryker\Client\ProductOfferServicePointAvailabilityCalculatorExtension\Dependency\Plugin\ProductOfferServicePointAvailabilityCalculatorStrategyPluginInterface} plugins.
     * - Executes default strategy to calculate availabilities if no applicable strategy found.
     * - Checks if there is an offer available for each product for selling in each service point.
     * - Returns a map of availabilities per service point by UUID for requested items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
     *
     * @return array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>>
     */
    public function calculateProductOfferServicePointAvailabilities(
        ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
    ): array;
}
