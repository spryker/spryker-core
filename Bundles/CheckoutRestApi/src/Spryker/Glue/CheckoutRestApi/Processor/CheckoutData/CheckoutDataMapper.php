<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\CheckoutData;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\RestAddressTransfer;
use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestPaymentMethodTransfer;
use Generated\Shared\Transfer\RestShipmentMethodTransfer;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;

class CheckoutDataMapper implements CheckoutDataMapperInterface
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
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    public function mapCheckoutDataTransferToRestCheckoutDataResponseAttributesTransfer(
        CheckoutDataTransfer $checkoutDataTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        $restCheckoutDataResponseAttributesTransfer = new RestCheckoutDataResponseAttributesTransfer();

        $restCheckoutDataResponseAttributesTransfer = $this->mapAddresses(
            $checkoutDataTransfer,
            $restCheckoutDataResponseAttributesTransfer
        );
        $restCheckoutDataResponseAttributesTransfer = $this->mapPaymentMethods(
            $checkoutDataTransfer,
            $restCheckoutDataResponseAttributesTransfer
        );
        $restCheckoutDataResponseAttributesTransfer = $this->mapShipmentMethods(
            $checkoutDataTransfer,
            $restCheckoutDataResponseAttributesTransfer
        );

        return $restCheckoutDataResponseAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    protected function mapAddresses(
        CheckoutDataTransfer $checkoutDataTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        foreach ($checkoutDataTransfer->getAddresses()->getAddresses() as $addressTransfer) {
            $restCheckoutDataResponseAttributesTransfer->addAddresses(
                (new RestAddressTransfer())->fromArray(
                    $addressTransfer->toArray(),
                    true
                )->setId($addressTransfer->getUuid())
            );
        }

        return $restCheckoutDataResponseAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    protected function mapPaymentMethods(
        CheckoutDataTransfer $checkoutDataTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        foreach ($checkoutDataTransfer->getPaymentMethods()->getMethods() as $paymentMethodTransfer) {
            $restPaymentMethodTransfer = new RestPaymentMethodTransfer();
            $restPaymentMethodTransfer->fromArray($paymentMethodTransfer->toArray(), true)
                ->setRequiredRequestData($this->config->getRequiredRequestDataForMethod($paymentMethodTransfer->getMethodName()));

            $restCheckoutDataResponseAttributesTransfer->addPaymentMethods($restPaymentMethodTransfer);
        }

        return $restCheckoutDataResponseAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    protected function mapShipmentMethods(
        CheckoutDataTransfer $checkoutDataTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        foreach ($checkoutDataTransfer->getShipmentMethods()->getMethods() as $shipmentMethodTransfer) {
            $restShipmentMethodTransfer = new RestShipmentMethodTransfer();
            $restShipmentMethodTransfer->fromArray($shipmentMethodTransfer->toArray(), true)
                ->setPrice($shipmentMethodTransfer->getStoreCurrencyPrice())
                ->setId($shipmentMethodTransfer->getIdShipmentMethod());

            $restCheckoutDataResponseAttributesTransfer->addShipmentMethods($restShipmentMethodTransfer);
        }

        return $restCheckoutDataResponseAttributesTransfer;
    }
}
