<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Plugin\ShipmentsRestApi;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin\ShippingAddressValidationStrategyPluginInterface;

/**
 * @method \Spryker\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiFactory getFactory()
 */
class MultiShipmentTypeServicePointShippingAddressValidationStrategyPlugin extends AbstractPlugin implements ShippingAddressValidationStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if multi-shipment request is given and at least one of the provided shipment methods is related to applicable shipment type.
     * - Uses {@link \Spryker\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiConfig::getApplicableShipmentTypeKeysForShippingAddress()} to get applicable shipment types keys.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return bool
     */
    public function isApplicable(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): bool
    {
        $restCheckoutRequestAttributesChecker = $this->getFactory()->createRestCheckoutRequestAttributesChecker();

        return $restCheckoutRequestAttributesTransfer->getShipments()->count() > 0
            && $restCheckoutRequestAttributesChecker->hasApplicableShipmentTypes($restCheckoutRequestAttributesTransfer);
    }

    /**
     * {@inheritDoc}
     * - Gets shipment types from storage for the current store.
     * - Uses {@link \Spryker\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiConfig::getApplicableShipmentTypeKeysForShippingAddress()} to get applicable shipment types keys.
     * - Checks if shipping address is defined for each applicable element in `RestCheckoutRequestAttributesTransfer.shipments`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    public function validate(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestErrorCollectionTransfer
    {
        return $this->getFactory()
            ->createMultiShippingAddressValidator()
            ->validate($restCheckoutRequestAttributesTransfer);
    }
}
