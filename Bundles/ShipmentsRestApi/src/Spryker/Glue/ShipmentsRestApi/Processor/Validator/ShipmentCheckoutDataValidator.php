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
use Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin\ShippingAddressValidationStrategyPluginInterface;
use Symfony\Component\HttpFoundation\Response;

class ShipmentCheckoutDataValidator implements ShipmentCheckoutDataValidatorInterface
{
    /**
     * @var \Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiConfig
     */
    protected ShipmentsRestApiConfig $shipmentsRestApiConfig;

    /**
     * @var list<\Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin\ShippingAddressValidationStrategyPluginInterface>
     */
    protected array $shippingAddressValidationStrategyPlugins;

    /**
     * @param \Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiConfig $shipmentsRestApiConfig
     * @param list<\Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin\ShippingAddressValidationStrategyPluginInterface> $shippingAddressValidationStrategyPlugins
     */
    public function __construct(ShipmentsRestApiConfig $shipmentsRestApiConfig, array $shippingAddressValidationStrategyPlugins)
    {
        $this->shipmentsRestApiConfig = $shipmentsRestApiConfig;
        $this->shippingAddressValidationStrategyPlugins = $shippingAddressValidationStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    public function validateShipmentCheckoutData(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestErrorCollectionTransfer {
        $restErrorCollectionTransfer = new RestErrorCollectionTransfer();
        if ($this->isShipmentLevelMixed($restCheckoutRequestAttributesTransfer)) {
            return $restErrorCollectionTransfer->addRestError(
                $this->buildErrorMessage(
                    ShipmentsRestApiConfig::ERROR_RESPONSE_DETAIL_SINGLE_MULTI_SHIPMENT_MIX,
                    ShipmentsRestApiConfig::ERROR_RESPONSE_CODE_SINGLE_MULTI_SHIPMENT_MIX,
                ),
            );
        }

        $shippingAddressValidationStrategyPlugin = $this->findShippingAddressValidationStrategyPlugin($restCheckoutRequestAttributesTransfer);
        if ($shippingAddressValidationStrategyPlugin) {
            return $shippingAddressValidationStrategyPlugin->validate($restCheckoutRequestAttributesTransfer);
        }

        if (!$this->isSingleShipmentLevelValid($restCheckoutRequestAttributesTransfer)) {
            return $restErrorCollectionTransfer->addRestError(
                $this->buildErrorMessage(
                    ShipmentsRestApiConfig::ERROR_RESPONSE_DETAIL_SHIPMENT_ATTRIBUTE_NOT_SPECIFIED,
                    ShipmentsRestApiConfig::ERROR_RESPONSE_CODE_SHIPMENT_ATTRIBUTE_NOT_SPECIFIED,
                ),
            );
        }

        if (!$this->isMultiShipmentLevelValid($restCheckoutRequestAttributesTransfer)) {
            return $restErrorCollectionTransfer->addRestError(
                $this->buildErrorMessage(
                    ShipmentsRestApiConfig::ERROR_RESPONSE_DETAIL_SHIPMENTS_ATTRIBUTE_NOT_SPECIFIED,
                    ShipmentsRestApiConfig::ERROR_RESPONSE_CODE_SHIPMENTS_ATTRIBUTE_NOT_SPECIFIED,
                ),
            );
        }

        return $restErrorCollectionTransfer;
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

        foreach ($restCheckoutRequestAttributesTransfer->getShipments() as $restShipmentsTransfer) {
            if ($restShipmentsTransfer->getShippingAddress() === null) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $detail
     * @param string $code
     * @param int|null $status
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function buildErrorMessage(
        string $detail,
        string $code,
        ?int $status = Response::HTTP_UNPROCESSABLE_ENTITY
    ): RestErrorMessageTransfer {
        return (new RestErrorMessageTransfer())
            ->setDetail($detail)
            ->setCode($code)
            ->setStatus($status);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin\ShippingAddressValidationStrategyPluginInterface|null
     */
    protected function findShippingAddressValidationStrategyPlugin(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): ?ShippingAddressValidationStrategyPluginInterface {
        if (!$this->shipmentsRestApiConfig->shouldExecuteShippingAddressValidationStrategyPlugins()) {
            return null;
        }

        foreach ($this->shippingAddressValidationStrategyPlugins as $addressValidationStrategyPlugin) {
            if ($addressValidationStrategyPlugin->isApplicable($restCheckoutRequestAttributesTransfer)) {
                return $addressValidationStrategyPlugin;
            }
        }

        return null;
    }
}
