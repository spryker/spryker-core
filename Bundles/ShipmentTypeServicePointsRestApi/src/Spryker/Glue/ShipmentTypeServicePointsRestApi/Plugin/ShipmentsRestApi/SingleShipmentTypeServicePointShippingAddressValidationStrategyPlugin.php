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
 * @deprecated Exists for Backward Compatibility reasons only.
 *             Use {@link \Spryker\Glue\ShipmentTypeServicePointsRestApi\Plugin\ShipmentsRestApi\MultiShipmentTypeServicePointShippingAddressValidationStrategyPlugin} instead.
 *
 * @method \Spryker\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiFactory getFactory()
 */
class SingleShipmentTypeServicePointShippingAddressValidationStrategyPlugin extends AbstractPlugin implements ShippingAddressValidationStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if single-shipment request is given and given shipment method is related to applicable shipment type.
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

        return $restCheckoutRequestAttributesTransfer->getShipment() !== null
            && $restCheckoutRequestAttributesChecker->hasApplicableShipmentTypes($restCheckoutRequestAttributesTransfer);
    }

    /**
     * {@inheritDoc}
     * - Checks if one service point is provided.
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
            ->createSingleShippingAddressValidator()
            ->validate($restCheckoutRequestAttributesTransfer);
    }
}
