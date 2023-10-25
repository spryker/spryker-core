<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutRequestAttributesValidatorPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiFactory getFactory()
 */
class ShipmentTypeServicePointCheckoutRequestAttributesValidatorPlugin extends AbstractPlugin implements CheckoutRequestAttributesValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing if each element in `RestCheckoutRequestAttributesTransfer.shipments` has non-applicable shipment type for the multi-shipment request.
     * - Does nothing if `RestCheckoutRequestAttributesTransfer.shipment` has non-applicable shipment type for the single-shipment request.
     * - Gets shipment type storage collection for the current store.
     * - Uses {@link \Spryker\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiConfig::getApplicableShipmentTypeKeysForShippingAddress()} to determine which shipment types are applicable.
     * - Checks that service point is provided for each element in `RestCheckoutRequestAttributesTransfer.shipments` with applicable shipment type.
     * - Checks that each element in `RestCheckoutRequestAttributesTransfer.servicePoints` has an address.
     * - Checks that `firstName`, `lastName` and `email` fields are provided for `RestCheckoutRequestAttributesTransfer.customer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    public function validateAttributes(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestErrorCollectionTransfer
    {
        return $this->getFactory()
            ->createShipmentTypeServicePointValidatorStrategyResolver()
            ->resolveValidator($restCheckoutRequestAttributesTransfer)
            ->validate($restCheckoutRequestAttributesTransfer);
    }
}
