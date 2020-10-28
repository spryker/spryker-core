<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutRequestAttributesValidatorPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiFactory getFactory()
 */
class ShipmentDataCheckoutRequestAttributesValidatorPlugin extends AbstractPlugin implements CheckoutRequestAttributesValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates if `RestCheckoutRequestAttributesTransfer` provides shipment data per item or on the top level.
     * - Passing `RestCheckoutRequestAttributesTransfer.shippingAddress` + `RestCheckoutRequestAttributesTransfer.shipment` is valid.
     * - Passing `RestCheckoutRequestAttributesTransfer.shipments` is valid.
     * - Mixing the multi-shipment and single-shipment is not valid.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    public function validateAttributes(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestErrorCollectionTransfer {
        return $this->getFactory()
            ->createShipmentCheckoutDataValidator()
            ->validateShipmentCheckoutData($restCheckoutRequestAttributesTransfer);
    }
}
