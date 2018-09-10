<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentRequestTransfer;
use Generated\Shared\Transfer\RatepayResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPreCheckPluginInterface;

/**
 * @method \Spryker\Zed\Ratepay\Business\RatepayFacadeInterface getFacade()
 * @method \Spryker\Zed\Ratepay\Communication\RatepayCommunicationFactory getFactory()
 */
class RatepayPreCheckPlugin extends AbstractPlugin implements CheckoutPreCheckPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return bool
     */
    public function execute(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        return $this->checkCondition($quoteTransfer, $checkoutResponse);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkCondition(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        $ratepayPaymentInitTransfer = $this->getFactory()->createPaymentInitTransfer();
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

        $partialOrderTransfer = $this->getPartialOrderTransferByBasketItems($quoteTransfer->getItems());

        $ratepayPaymentRequestTransfer = new RatepayPaymentRequestTransfer();
        $quotePaymentInitMapper = $this->getFactory()->createPaymentRequestMapperByQuote(
            $ratepayPaymentRequestTransfer,
            $ratepayPaymentInitTransfer,
            $quoteTransfer,
            $partialOrderTransfer,
            $paymentData
        );
        $quotePaymentInitMapper->map();

        $ratepayResponseTransfer = $this->getFacade()->requestPayment($ratepayPaymentRequestTransfer);
        $this->getFacade()->updatePaymentMethodByPaymentResponse($ratepayResponseTransfer, $ratepayPaymentRequestTransfer->getOrderId());

        return $this->checkForErrors($ratepayResponseTransfer, $checkoutResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayResponseTransfer $ratepayResponseTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
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
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $basketItems
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getPartialOrderTransferByBasketItems($basketItems)
    {
        $partialOrderTransfer = $this->getFactory()->createOrderTransfer();
        $items = $this->getFactory()->createOrderTransferItemsByBasketItems($basketItems);
        $partialOrderTransfer->setItems($items);

        return $this
            ->getFactory()
            ->getSalesAggregator()
            ->getOrderTotalByOrderTransfer($partialOrderTransfer);
    }
}
