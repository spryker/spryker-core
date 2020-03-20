<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataResponseMapper;

use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;

class ShipmentMethodCheckoutDataResponseMapper implements CheckoutDataResponseMapperInterface
{
    /**
     * @var \Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig $config
     */
    public function __construct(CheckoutRestApiConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    public function map(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        if ($this->config->isShipmentMethodsMappedToAttributes()) {
            $restCheckoutDataResponseAttributesTransfer = $this->mapShipmentMethods(
                $restCheckoutDataTransfer,
                $restCheckoutDataResponseAttributesTransfer
            );
        }

        return $restCheckoutDataResponseAttributesTransfer;
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $checkoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    protected function mapShipmentMethods(
        RestCheckoutDataTransfer $checkoutDataTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        $shipmentMethodTransfers = $checkoutDataTransfer->getShipmentMethods()->getMethods();
        foreach ($shipmentMethodTransfers as $shipmentMethodTransfer) {
            $restShipmentMethodTransfer = $this->mapShipmentMethodTransferToRestShipmentMethodTransfer(
                $shipmentMethodTransfer,
                new RestShipmentMethodTransfer()
            );

            $restCheckoutDataResponseAttributesTransfer->addShipmentMethod($restShipmentMethodTransfer);
        }

        return $restCheckoutDataResponseAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\RestShipmentMethodTransfer $restShipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\RestShipmentMethodTransfer
     */
    protected function mapShipmentMethodTransferToRestShipmentMethodTransfer(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        RestShipmentMethodTransfer $restShipmentMethodTransfer
    ): RestShipmentMethodTransfer {
        $restShipmentMethodTransfer
            ->fromArray($shipmentMethodTransfer->toArray(), true)
            ->setPrice($shipmentMethodTransfer->getStoreCurrencyPrice())
            ->setId($shipmentMethodTransfer->getIdShipmentMethod());

        return $restShipmentMethodTransfer;
    }
}
