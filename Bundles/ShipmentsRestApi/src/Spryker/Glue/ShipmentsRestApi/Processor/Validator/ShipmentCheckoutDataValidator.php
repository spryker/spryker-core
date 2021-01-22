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
        if ($this->isShipmentLevelMixed($restCheckoutRequestAttributesTransfer)) {
            return $this->buildErrorMessage(
                ShipmentsRestApiConfig::ERROR_RESPONSE_DETAIL_SINGLE_MULTI_SHIPMENT_MIX,
                ShipmentsRestApiConfig::ERROR_RESPONSE_CODE_SINGLE_MULTI_SHIPMENT_MIX
            );
        }

        if (!$this->isSingleShipmentLevelValid($restCheckoutRequestAttributesTransfer)) {
            return $this->buildErrorMessage(
                ShipmentsRestApiConfig::ERROR_RESPONSE_DETAIL_SHIPMENT_ATTRIBUTE_NOT_SPECIFIED,
                ShipmentsRestApiConfig::ERROR_RESPONSE_CODE_SHIPMENT_ATTRIBUTE_NOT_SPECIFIED
            );
        }

        if (!$this->isMultiShipmentLevelValid($restCheckoutRequestAttributesTransfer)) {
            return $this->buildErrorMessage(
                ShipmentsRestApiConfig::ERROR_RESPONSE_DETAIL_SHIPMENTS_ATTRIBUTE_NOT_SPECIFIED,
                ShipmentsRestApiConfig::ERROR_RESPONSE_CODE_SHIPMENTS_ATTRIBUTE_NOT_SPECIFIED
            );
        }

        return new RestErrorCollectionTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return bool
     */
    protected function isShipmentLevelMixed(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): bool
    {
        if (
            $restCheckoutRequestAttributesTransfer->getShipments()->count()
            && ($restCheckoutRequestAttributesTransfer->getShipment() || $restCheckoutRequestAttributesTransfer->getShippingAddress())
        ) {
            return true;
        }

        return false;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return bool
     */
    protected function isSingleShipmentLevelValid(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): bool
    {
        if ($restCheckoutRequestAttributesTransfer->getShipments()->count()) {
            return true;
        }

        if (!$restCheckoutRequestAttributesTransfer->getShipment() || !$restCheckoutRequestAttributesTransfer->getShippingAddress()) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return bool
     */
    protected function isMultiShipmentLevelValid(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): bool
    {
        if ($restCheckoutRequestAttributesTransfer->getShipment() || $restCheckoutRequestAttributesTransfer->getShippingAddress()) {
            return true;
        }

        if (!$restCheckoutRequestAttributesTransfer->getShipments()->count()) {
            return false;
        }

        return true;
    }

    /**
     * @param string $detail
     * @param string $code
     * @param int|null $status
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    protected function buildErrorMessage(
        string $detail,
        string $code,
        ?int $status = Response::HTTP_UNPROCESSABLE_ENTITY
    ): RestErrorCollectionTransfer {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setDetail($detail)
            ->setCode($code)
            ->setStatus($status);

        return (new RestErrorCollectionTransfer())
            ->addRestError($restErrorMessageTransfer);
    }
}
