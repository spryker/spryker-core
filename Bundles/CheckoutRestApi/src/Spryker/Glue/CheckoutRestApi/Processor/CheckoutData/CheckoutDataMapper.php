<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\CheckoutData;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestAddressTransfer;
use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestPaymentMethodAttributesTransfer;
use Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
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

        $restCheckoutDataResponseAttributesTransfer = $this->mapAddressesTransferToRestAddressTransfer(
            $checkoutDataTransfer,
            $restCheckoutDataResponseAttributesTransfer
        );
        $restCheckoutDataResponseAttributesTransfer = $this->mapPaymentMethodsTransferToRestPaymentMethodResponseTransfer(
            $checkoutDataTransfer,
            $restCheckoutDataResponseAttributesTransfer
        );
        $restCheckoutDataResponseAttributesTransfer = $this->mapShipmentMethodsTransferToRestShipmentMethodResponseTransfer(
            $checkoutDataTransfer,
            $restCheckoutDataResponseAttributesTransfer
        );

        return $this->mapRestCheckoutRequestAttributesTransferToRestCheckoutDataResponseAttributesTransfer(
            $restCheckoutRequestAttributesTransfer,
            $restCheckoutDataResponseAttributesTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param bool $checkRequired
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapRestCheckoutRequestAttributesTransferToQuoteTransfer(
        QuoteTransfer $quoteTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        bool $checkRequired = true
    ): QuoteTransfer {
        $quoteTransfer = $this->mapRestAddressTransfersToQuoteTransfer($quoteTransfer, $restCheckoutRequestAttributesTransfer, $checkRequired);
        $quoteTransfer = $this->mapPaymentsToQuoteTransfer($quoteTransfer, $restCheckoutRequestAttributesTransfer, $checkRequired);
        $quoteTransfer = $this->mapRestShipmentTransferToQuoteTransfer($quoteTransfer, $restCheckoutRequestAttributesTransfer, $checkRequired);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    protected function mapRestCheckoutRequestAttributesTransferToRestCheckoutDataResponseAttributesTransfer(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        $restQuoteRequestTransfer = $restCheckoutRequestAttributesTransfer->getCart();
        $this->addRestAddressTransferToRestCheckoutDataResponseAttributesTransfer(
            $restCheckoutDataResponseAttributesTransfer,
            $restQuoteRequestTransfer->getBillingAddress()
        );
        $this->addRestAddressTransferToRestCheckoutDataResponseAttributesTransfer(
            $restCheckoutDataResponseAttributesTransfer,
            $restQuoteRequestTransfer->getShippingAddress()
        );

        return $restCheckoutDataResponseAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    protected function mapAddressesTransferToRestAddressTransfer(
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
    protected function mapPaymentMethodsTransferToRestPaymentMethodResponseTransfer(
        CheckoutDataTransfer $checkoutDataTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        foreach ($checkoutDataTransfer->getPaymentMethods()->getMethods() as $paymentMethodTransfer) {
            $restCheckoutDataResponseAttributesTransfer->addPaymentMethods(
                (new RestPaymentMethodAttributesTransfer())->fromArray(
                    $paymentMethodTransfer->toArray(),
                    true
                )->setRequiredResponseData($this->config->getRequiredPaymentDataForMethod($paymentMethodTransfer->getMethodName()))
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
    protected function mapShipmentMethodsTransferToRestShipmentMethodResponseTransfer(
        CheckoutDataTransfer $checkoutDataTransfer,
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        foreach ($checkoutDataTransfer->getShipmentMethods()->getMethods() as $shipmentMethodTransfer) {
            $restCheckoutDataResponseAttributesTransfer->addShipmentMethods(
                (new RestShipmentMethodAttributesTransfer())->fromArray(
                    $shipmentMethodTransfer->toArray(),
                    true
                )->setPrice($shipmentMethodTransfer->getStoreCurrencyPrice())
                ->setId($shipmentMethodTransfer->getIdShipmentMethod())
            );
        }

        return $restCheckoutDataResponseAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param bool $checkRequired
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mapRestAddressTransfersToQuoteTransfer(
        QuoteTransfer $quoteTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        bool $checkRequired = true
    ): QuoteTransfer {
        if ($checkRequired === true) {
            $restCheckoutRequestAttributesTransfer->getCart()->requireBillingAddress();
        }

        if ($restCheckoutRequestAttributesTransfer->getCart()->getBillingAddress() !== null) {
            $quoteTransfer->setBillingAddress(
                (new AddressTransfer())->fromArray(
                    $restCheckoutRequestAttributesTransfer->getCart()->getBillingAddress()->toArray(),
                    true
                )->setUuid($restCheckoutRequestAttributesTransfer->getCart()->getBillingAddress()->getId())
            );
        }

        if ($checkRequired === true) {
            $restCheckoutRequestAttributesTransfer->getCart()->requireShippingAddress();
        }

        if ($restCheckoutRequestAttributesTransfer->getCart()->getShippingAddress() !== null) {
            $quoteTransfer->setShippingAddress(
                (new AddressTransfer())->fromArray(
                    $restCheckoutRequestAttributesTransfer->getCart()->getShippingAddress()->toArray(),
                    true
                )->setUuid($restCheckoutRequestAttributesTransfer->getCart()->getShippingAddress()->getId())
            );
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param bool $checkRequired
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mapPaymentsToQuoteTransfer(
        QuoteTransfer $quoteTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        bool $checkRequired = true
    ): QuoteTransfer {
        if ($checkRequired === true) {
            $restCheckoutRequestAttributesTransfer->getCart()->requirePayments();
        }

        foreach ($restCheckoutRequestAttributesTransfer->getCart()->getPayments() as $paymentTransfer) {
            $quoteTransfer->addPayment($paymentTransfer);
            $quoteTransfer->setPayment($paymentTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param bool $checkRequired
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mapRestShipmentTransferToQuoteTransfer(
        QuoteTransfer $quoteTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        bool $checkRequired = true
    ): QuoteTransfer {
        if ($checkRequired === true) {
            $restCheckoutRequestAttributesTransfer->getCart()->requireShipment();
        }

        if ($restCheckoutRequestAttributesTransfer->getCart()->getShipment() !== null) {
            $quoteTransfer->setShipment(
                (new ShipmentTransfer())->fromArray(
                    $restCheckoutRequestAttributesTransfer->getCart()->getShipment()->toArray()
                )
            );

            if ($quoteTransfer->getShipment()->getMethod() !== null) {
                $quoteTransfer->getShipment()->getMethod()->setIdShipmentMethod(
                    $restCheckoutRequestAttributesTransfer->getCart()->getShipment()->getMethod()->getId()
                );
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\RestAddressTransfer[] $restAddressesResponseData
     * @param \Generated\Shared\Transfer\RestAddressTransfer $restAddressTransfer
     *
     * @return bool
     */
    protected function addressExists(ArrayObject $restAddressesResponseData, RestAddressTransfer $restAddressTransfer): bool
    {
        if ($restAddressTransfer->getId() !== null) {
            return true;
        }

        foreach ($restAddressesResponseData as $restAddressResponseTransfer) {
            if ($restAddressResponseTransfer->getId() !== null && $restAddressResponseTransfer->getId() == $restAddressTransfer->getId()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     * @param \Generated\Shared\Transfer\RestAddressTransfer|null $restAddressTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    protected function addRestAddressTransferToRestCheckoutDataResponseAttributesTransfer(
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer,
        ?RestAddressTransfer $restAddressTransfer
    ): RestCheckoutDataResponseAttributesTransfer {
        if ($restAddressTransfer !== null
            && !$this->addressExists(
                $restCheckoutDataResponseAttributesTransfer->getAddresses(),
                $restAddressTransfer
            )) {
            return $restCheckoutDataResponseAttributesTransfer->addAddresses($restAddressTransfer);
        }
    }
}
