<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Validator;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ShipmentCheckoutDataValidator implements ShipmentCheckoutDataValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    public function validateShipmentCheckoutData(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestErrorCollectionTransfer {
        $checkoutDataShippingAddress = $restCheckoutRequestAttributesTransfer->getShippingAddress();
        $checkoutDataShipment = $restCheckoutRequestAttributesTransfer->getShipment();
        $checkoutDataShipments = $restCheckoutRequestAttributesTransfer->getShipments();

        $restErrorCollectionTransfer = new RestErrorCollectionTransfer();
        if ($checkoutDataShipments->count() && ($checkoutDataShipment || $checkoutDataShippingAddress)) {
            $restErrorMessageTransfer = (new RestErrorMessageTransfer())
                ->setDetail(ShipmentsRestApiConfig::ERROR_RESPONSE_DETAIL_SINGLE_MULTI_SHIPMENT_MIX)
                ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->setCode(ShipmentsRestApiConfig::ERROR_RESPONSE_CODE_SINGLE_MULTI_SHIPMENT_MIX);

            $restErrorCollectionTransfer->addRestError($restErrorMessageTransfer);
        }

        return $restErrorCollectionTransfer;
    }
}
