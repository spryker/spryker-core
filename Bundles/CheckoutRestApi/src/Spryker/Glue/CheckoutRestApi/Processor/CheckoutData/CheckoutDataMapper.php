<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\CheckoutData;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestAddressTransfer;
use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestPaymentMethodAttributesTransfer;
use Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer;

class CheckoutDataMapper implements CheckoutDataMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer
     */
    public function mapCheckoutDataTransferToRestCheckoutDataResponseAttributesTransfer(CheckoutDataTransfer $checkoutDataTransfer): RestCheckoutDataResponseAttributesTransfer
    {
        $restCheckoutDataResponseAttributesTransfer = new RestCheckoutDataResponseAttributesTransfer();

        foreach ($checkoutDataTransfer->getAddresses()->getAddresses() as $addressTransfer) {
            $restCheckoutDataResponseAttributesTransfer->addRestAddressesResponseData(
                (new RestAddressTransfer())->fromArray(
                    $addressTransfer->toArray(),
                    true
                )
            );
        }

        foreach ($checkoutDataTransfer->getPaymentMethods()->getMethods() as $paymentMethodTransfer) {
            $restCheckoutDataResponseAttributesTransfer->addRestPaymentMethodsResponseData(
                (new RestPaymentMethodAttributesTransfer())->fromArray(
                    $paymentMethodTransfer->toArray(),
                    true
                )
            );
        }

        foreach ($checkoutDataTransfer->getShipmentMethods()->getMethods() as $shipmentMethodTransfer) {
            $restCheckoutDataResponseAttributesTransfer->addRestShipmentMethodsResponseData(
                (new RestShipmentMethodAttributesTransfer())->fromArray(
                    $shipmentMethodTransfer->toArray(),
                    true
                )
                ->setPrice(
                    $shipmentMethodTransfer->getStoreCurrencyPrice()
                )
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
    public function mapRestCheckoutRequestAttributesTransferToQuoteTransfer(QuoteTransfer $quoteTransfer, RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): QuoteTransfer
    {
        $quoteTransfer->setBillingAddress(
            (new AddressTransfer())->fromArray(
                $restCheckoutRequestAttributesTransfer->getQuote()->getBillingAddress()->toArray(),
                true
            )
        );
        $quoteTransfer->setShippingAddress(
            (new AddressTransfer())->fromArray(
                $restCheckoutRequestAttributesTransfer->getQuote()->getShippingAddress()->toArray(),
                true
            )
        );
        foreach ($restCheckoutRequestAttributesTransfer->getQuote()->getPayments() as $restPaymentTransfer) {
            $quoteTransfer->addPayment(
                (new PaymentTransfer())->fromArray(
                    $restPaymentTransfer->toArray(),
                    true
                )
            );
        }
//        $quoteTransfer->setShipment(
//            (new ShipmentTransfer())->fromArray(
//                $restCheckoutRequestAttributesTransfer->getQuote()->getShipment()->toArray()
//            )
//        );
        $quoteTransfer->setVoucherCode($restCheckoutRequestAttributesTransfer->getQuote()->getVoucherCode());

        return $quoteTransfer;
    }
}
