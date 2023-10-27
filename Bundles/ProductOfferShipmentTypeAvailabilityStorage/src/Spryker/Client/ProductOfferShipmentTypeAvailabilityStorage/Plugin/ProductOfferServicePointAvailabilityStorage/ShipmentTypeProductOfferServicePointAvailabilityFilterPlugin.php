<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferShipmentTypeAvailabilityStorage\Plugin\ProductOfferServicePointAvailabilityStorage;

use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductOfferServicePointAvailabilityStorageExtension\Dependency\Plugin\ProductOfferServicePointAvailabilityFilterPluginInterface;

/**
 * @method \Spryker\Client\ProductOfferShipmentTypeAvailabilityStorage\ProductOfferShipmentTypeAvailabilityStorageClient getClient()
 */
class ShipmentTypeProductOfferServicePointAvailabilityFilterPlugin extends AbstractPlugin implements ProductOfferServicePointAvailabilityFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ProductOfferServicePointAvailabilityCriteriaTransfer.productOfferServicePointAvailabilityConditions` to be set.
     * - Requires `ProductOfferServicePointAvailabilityResponseItemTransfer.productOfferStorage` to be set.
     * - Requires `ProductOfferServicePointAvailabilityResponseItemTransfer.productOfferStorage.shipmentTypes.uuid` to be set.
     * - Requires `ProductOfferServicePointAvailabilityConditionsTransfer.storeName` to be set.
     * - Expects `ProductOfferServicePointAvailabilityConditionsTransfer.productOfferServicePointAvailabilityResponseItems` to be set.
     * - Expects `ProductOfferServicePointAvailabilityResponseItemTransfer.productOfferStorage.shipmentTypes` to be set.
     * - Filters product offer service point availability transfers by `ProductOfferServicePointAvailabilityCriteriaTransfer.productOfferServicePointAvailabilityConditions.shipmentTypeUuid`.
     * - Returns `ProductOfferServicePointAvailabilityCollectionTransfer` filled with filtered product offer service point availability.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer
     */
    public function filter(
        ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer,
        ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer
    ): ProductOfferServicePointAvailabilityCollectionTransfer {
        return $this->getClient()->filterProductOfferServicePointAvailabilityCollection(
            $productOfferServicePointAvailabilityCriteriaTransfer,
            $productOfferServicePointAvailabilityCollectionTransfer,
        );
    }
}
