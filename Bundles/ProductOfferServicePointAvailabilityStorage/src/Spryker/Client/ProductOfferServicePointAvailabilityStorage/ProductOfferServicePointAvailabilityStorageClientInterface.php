<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityStorage;

use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer;

interface ProductOfferServicePointAvailabilityStorageClientInterface
{
    /**
     * Specification:
     * - Requires `ProductOfferServicePointAvailabilityCriteriaTransfer.productOfferServicePointAvailabilityConditions` to be set.
     * - Requires `ProductOfferServicePointAvailabilityCriteriaTransfer.productOfferServicePointAvailabilityConditions.storeName` to be set.
     * - Requires `ProductOfferServicePointAvailabilityCriteriaTransfer.productOfferServicePointAvailabilityConditions.servicePointUuids` to be set.
     * - Requires `ProductOfferServicePointAvailabilityCriteriaTransfer.productOfferServicePointAvailabilityConditions.serviceTypeUuid` to be set.
     * - Requires `ProductOfferServicePointAvailabilityCriteriaTransfer.productOfferServicePointAvailabilityConditions.productOfferServicePointAvailabilityRequestItems` to be set.
     * - Requires `ProductOfferServicePointAvailabilityRequestItemTransfer.productConcreteSku` to be set.
     * - Finds product offer storage transfers by `ProductOfferServicePointAvailabilityRequestItemTransfer.productConcreteSku`.
     * - Filters product offer storage transfers by `ProductOfferServicePointAvailabilityCriteriaTransfer.productOfferServicePointAvailabilityConditions.servicePointUuids`.
     * - Filters product offer storage transfers by `ProductOfferServicePointAvailabilityCriteriaTransfer.productOfferServicePointAvailabilityConditions.serviceType`.
     * - Finds product offer storage availability transfers by filtered product offer storage transfers.
     * - Executes stack of {@link \Spryker\Client\ProductOfferServicePointAvailabilityStorageExtension\Dependency\Plugin\ProductOfferServicePointAvailabilityFilterPluginInterface} plugins.
     * - Returns `ProductOfferServicePointAvailabilityCollectionTransfer` filled with product offer service point availability.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer
     */
    public function getProductOfferServicePointAvailabilityCollection(
        ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
    ): ProductOfferServicePointAvailabilityCollectionTransfer;
}
