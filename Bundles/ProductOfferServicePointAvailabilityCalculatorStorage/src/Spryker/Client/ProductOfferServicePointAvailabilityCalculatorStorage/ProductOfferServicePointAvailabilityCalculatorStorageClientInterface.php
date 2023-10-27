<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage;

use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer;

interface ProductOfferServicePointAvailabilityCalculatorStorageClientInterface
{
    /**
     * Specification:
     * - Requires `ProductOfferServicePointAvailabilityCriteriaTransfer.productOfferServicePointAvailabilityConditions` to be set.
     * - Requires `ProductOfferServicePointAvailabilityConditionsTransfer.productOfferServicePointAvailabilityRequestItems` to be set.
     * - Requires `ProductOfferServicePointAvailabilityRequestItemTransfer.productConcreteSku` to be set.
     * - Requires `ProductOfferServicePointAvailabilityRequestItemTransfer.quantity` to be set.
     * - Expects `ProductOfferServicePointAvailabilityRequestItemTransfer.productOfferReference` to be set.
     * - If `ProductOfferServicePointAvailabilityConditionsTransfer.storeName` is not set, uses current store name.
     * - Finds applicable strategy by executing {@link \Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorageExtension\Dependency\Plugin\ProductOfferServicePointAvailabilityCalculatorStrategyPluginInterface} plugins.
     * - Executes default strategy to calculate availabilities if no applicable strategy found.
     * - Checks if offer is available for selling in each service point.
     * - Items without `ProductOfferServicePointAvailabilityRequestItemTransfer.productOfferReference` set are recognized as not available.
     * - Returns a map of availabilities per service point by UUID for requested items.
     * - Response items in returned map have `identifier` property which matches their initial index in request items array.
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
