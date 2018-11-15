<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Checkout;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutRestApi\Business\Customer\QuoteCustomerExpanderInterface;
use Spryker\Zed\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartsRestApiFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCheckoutFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToQuoteFacadeInterface;
use Symfony\Component\HttpFoundation\Response;

class PlaceOrderProcessor implements PlaceOrderProcessorInterface
{
    /**
     * @var \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartsRestApiFacadeInterface
     */
    protected $cartsRestApiFacade;

    /**
     * @var \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCheckoutFacadeInterface
     */
    protected $checkoutFacade;

    /**
     * @var \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\CheckoutRestApi\Business\Customer\QuoteCustomerExpanderInterface
     */
    protected $quoteCustomerExpander;

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartsRestApiFacadeInterface $cartsRestApiFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCheckoutFacadeInterface $checkoutFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\CheckoutRestApi\Business\Customer\QuoteCustomerExpanderInterface $quoteCustomerExpander
     */
    public function __construct(
        CheckoutRestApiToCartFacadeInterface $cartFacade,
        CheckoutRestApiToCartsRestApiFacadeInterface $cartsRestApiFacade,
        CheckoutRestApiToCheckoutFacadeInterface $checkoutFacade,
        CheckoutRestApiToQuoteFacadeInterface $quoteFacade,
        QuoteCustomerExpanderInterface $quoteCustomerExpander
    ) {
        $this->cartFacade = $cartFacade;
        $this->cartsRestApiFacade = $cartsRestApiFacade;
        $this->checkoutFacade = $checkoutFacade;
        $this->quoteFacade = $quoteFacade;
        $this->quoteCustomerExpander = $quoteCustomerExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function placeOrder(QuoteTransfer $quoteTransfer): CheckoutResponseTransfer
    {
        $currentQuoteTransfer = $this->cartsRestApiFacade
            ->findQuoteByUuid(
                $quoteTransfer->getUuid(),
                (new QuoteCriteriaFilterTransfer())->setCustomerReference($quoteTransfer->getCustomer()->getCustomerReference())
            );

        if (!$currentQuoteTransfer) {
            return (new CheckoutResponseTransfer())
                ->setIsSuccess(false)
                ->addError(
                    (new CheckoutErrorTransfer())
                        ->setErrorCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                        ->setMessage(CheckoutRestApiConfig::ERROR_MESSAGE_CART_NOT_FOUND)
                );
        }

        $currentQuoteTransfer = $this->mergeSavedQuoteWithIncomingQuote($currentQuoteTransfer, $quoteTransfer);

        $paymentTransfer = $currentQuoteTransfer->getPayment();

        $quoteResponseTransfer = $this->cartFacade->validateQuote($currentQuoteTransfer);
        if ($quoteResponseTransfer->getIsSuccessful() === false) {
            return $this->createCheckoutResponseTransferFromQuoteErrorTransfer($quoteResponseTransfer);
        }

        $currentQuoteTransfer = $this->restorePaymentInQuote($quoteResponseTransfer->getQuoteTransfer(), $paymentTransfer);

        $checkoutResponseTransfer = $this->checkoutFacade->placeOrder($currentQuoteTransfer);
        if (!$checkoutResponseTransfer->getIsSuccess()) {
            return $checkoutResponseTransfer;
        }

        $quoteResponseTransfer = $this->quoteFacade->deleteQuote($currentQuoteTransfer);
        if ($quoteResponseTransfer->getIsSuccessful() === false) {
            return $this->createCheckoutResponseTransferFromQuoteErrorTransfer($quoteResponseTransfer);
        }

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function restorePaymentInQuote(QuoteTransfer $quoteTransfer, PaymentTransfer $paymentTransfer): QuoteTransfer
    {
        $paymentTransfer->setAmount($quoteTransfer->getTotals()->getPriceToPay());
        $quoteTransfer->setPayment($paymentTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function createCheckoutResponseTransferFromQuoteErrorTransfer(QuoteResponseTransfer $quoteResponseTransfer): CheckoutResponseTransfer
    {
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $checkoutResponseTransfer->setIsSuccess(false);
        foreach ($quoteResponseTransfer->getErrors() as $quoteErrorTransfer) {
            $checkoutResponseTransfer->addError(
                (new CheckoutErrorTransfer())
                    ->setMessage($quoteErrorTransfer->getMessage())
            );
        }

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $incomingQuoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mergeSavedQuoteWithIncomingQuote(QuoteTransfer $quoteTransfer, QuoteTransfer $incomingQuoteTransfer): QuoteTransfer
    {
        $quoteTransfer->setBillingAddress($incomingQuoteTransfer->getBillingAddress());
        $quoteTransfer->setShippingAddress($incomingQuoteTransfer->getShippingAddress());
        $quoteTransfer->setPayment($incomingQuoteTransfer->getPayment());
        $quoteTransfer->setShipment($incomingQuoteTransfer->getShipment());
        $quoteTransfer->setCustomer($incomingQuoteTransfer->getCustomer());

        $quoteTransfer = $this->quoteCustomerExpander->expandQuoteWithCustomerData($quoteTransfer);

        return $quoteTransfer;
    }
}
