<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\CheckoutData;

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
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    public function mapCheckoutDataTransferToRestCheckoutDataResponseAttributesTransfer(CheckoutDataTransfer $checkoutDataTransfer): RestCheckoutDataResponseAttributesTransfer
    {
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

        return $restCheckoutDataResponseAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapRestCheckoutRequestAttributesTransferToQuoteTransfer(
        QuoteTransfer $quoteTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): QuoteTransfer {
        $quoteTransfer = $this->mapRestAddressTransfersToQuoteTransfer($quoteTransfer, $restCheckoutRequestAttributesTransfer);
        $quoteTransfer = $this->mapPaymentsToQuoteTransfer($quoteTransfer, $restCheckoutRequestAttributesTransfer);
        $quoteTransfer = $this->mapRestShipmentTransferToQuoteTransfer($quoteTransfer, $restCheckoutRequestAttributesTransfer);
        $quoteTransfer = $this->mapRestVoucherCodeToQuoteTransfer($quoteTransfer, $restCheckoutRequestAttributesTransfer);

        return $quoteTransfer;
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
            $restCheckoutDataResponseAttributesTransfer->addRestAddressesResponseData(
                (new RestAddressTransfer())->fromArray(
                    $addressTransfer->toArray(),
                    true
                )
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
            $restCheckoutDataResponseAttributesTransfer->addRestPaymentMethodsResponseData(
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
            $restCheckoutDataResponseAttributesTransfer->addRestShipmentMethodsResponseData(
                (new RestShipmentMethodAttributesTransfer())->fromArray(
                    $shipmentMethodTransfer->toArray(),
                    true
                )->setPrice($shipmentMethodTransfer->getStoreCurrencyPrice())
            );
        }

        return $restCheckoutDataResponseAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mapRestAddressTransfersToQuoteTransfer(
        QuoteTransfer $quoteTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): QuoteTransfer {
        $restCheckoutRequestAttributesTransfer->getQuote()->requireBillingAddress();
        $quoteTransfer->setBillingAddress(
            (new AddressTransfer())->fromArray(
                $restCheckoutRequestAttributesTransfer->getQuote()->getBillingAddress()->toArray(),
                true
            )
        );

        $restCheckoutRequestAttributesTransfer->getQuote()->requireShippingAddress();
        $quoteTransfer->setShippingAddress(
            (new AddressTransfer())->fromArray(
                $restCheckoutRequestAttributesTransfer->getQuote()->getShippingAddress()->toArray(),
                true
            )
        );

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mapPaymentsToQuoteTransfer(
        QuoteTransfer $quoteTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): QuoteTransfer {
        foreach ($restCheckoutRequestAttributesTransfer->getQuote()->getPayments() as $paymentTransfer) {
            $quoteTransfer->addPayment($paymentTransfer);
            $quoteTransfer->setPayment($paymentTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mapRestShipmentTransferToQuoteTransfer(
        QuoteTransfer $quoteTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): QuoteTransfer {
        $restCheckoutRequestAttributesTransfer->getQuote()->requireShipment();

        $quoteTransfer->setShipment(
            (new ShipmentTransfer())->fromArray(
                $restCheckoutRequestAttributesTransfer->getQuote()->getShipment()->toArray()
            )
        );

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mapRestVoucherCodeToQuoteTransfer(
        QuoteTransfer $quoteTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): QuoteTransfer {
        $quoteTransfer->setVoucherCode($restCheckoutRequestAttributesTransfer->getQuote()->getVoucherCode());

        return $quoteTransfer;
    }
}
