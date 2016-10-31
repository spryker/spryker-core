<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentInitTransfer;
use Generated\Shared\Transfer\RatepayPaymentRequestTransfer;
use Generated\Shared\Transfer\RatepayResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPreCheckPluginInterface;

/**
 * @method \Spryker\Zed\Ratepay\Business\RatepayFacade getFacade()
 * @method \Spryker\Zed\Ratepay\Communication\RatepayCommunicationFactory getFactory()
 */
class RatepayPreCheckPlugin extends AbstractPlugin implements CheckoutPreCheckPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        return $this->checkCondition($quoteTransfer, $checkoutResponse);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function checkCondition(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        $ratepayPaymentInitTransfer = new RatepayPaymentInitTransfer();
        $quotePaymentInitMapper = $this->getFactory()->createPaymentInitMapperByQuote(
            $ratepayPaymentInitTransfer,
            $quoteTransfer
        );
        $quotePaymentInitMapper->map();

        $ratepayResponseTransfer = $this->getFacade()->initPayment($ratepayPaymentInitTransfer);
        $paymentData = $this->getFactory()
            ->getPaymentMethodExtractor()
            ->extractPaymentMethod($quoteTransfer);
        if ($paymentData) {
            $paymentData
                ->setTransactionId($ratepayResponseTransfer->getTransactionId())
                ->setTransactionShortId($ratepayResponseTransfer->getTransactionShortId())
                ->setResultCode($ratepayResponseTransfer->getStatusCode());
        }
        $ratepayPaymentRequestTransfer = new RatepayPaymentRequestTransfer();
        $quotePaymentInitMapper = $this->getFactory()->createPaymentRequestMapperByQuote(
            $ratepayPaymentRequestTransfer,
            $ratepayPaymentInitTransfer,
            $quoteTransfer,
            $paymentData
        );
        $quotePaymentInitMapper->map();

        $ratepayResponseTransfer = $this->getFacade()->requestPayment($ratepayPaymentRequestTransfer);
        $this->checkForErrors($ratepayResponseTransfer, $checkoutResponseTransfer);

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayResponseTransfer $ratepayResponseTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function checkForErrors(RatepayResponseTransfer $ratepayResponseTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        if (!$ratepayResponseTransfer->getSuccessful()) {

            $errorMessage = $ratepayResponseTransfer->getCustomerMessage() != '' ? $ratepayResponseTransfer->getCustomerMessage() :
                $ratepayResponseTransfer->getResultText();

            $error = new CheckoutErrorTransfer();
            $error
                ->setErrorCode($ratepayResponseTransfer->getResultCode())
                ->setMessage($errorMessage);
            $checkoutResponseTransfer->addError($error);
        }
    }

}
