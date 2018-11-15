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
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
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
     * @var array
     */
    protected $quoteMappingPlugins;

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartsRestApiFacadeInterface $cartsRestApiFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCheckoutFacadeInterface $checkoutFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToQuoteFacadeInterface $quoteFacade
     * @param array $quoteMappingPlugins
     */
    public function __construct(
        CheckoutRestApiToCartFacadeInterface $cartFacade,
        CheckoutRestApiToCartsRestApiFacadeInterface $cartsRestApiFacade,
        CheckoutRestApiToCheckoutFacadeInterface $checkoutFacade,
        CheckoutRestApiToQuoteFacadeInterface $quoteFacade,
        array $quoteMappingPlugins
    ) {
        $this->cartFacade = $cartFacade;
        $this->cartsRestApiFacade = $cartsRestApiFacade;
        $this->checkoutFacade = $checkoutFacade;
        $this->quoteFacade = $quoteFacade;
        $this->quoteMappingPlugins = $quoteMappingPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function placeOrder(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): CheckoutResponseTransfer
    {
        $quoteTransfer = $this->findCustomerQuote($restCheckoutRequestAttributesTransfer);

        if ($quoteTransfer === null) {
            return $this->createCartNotFoundErrorResponse();
        }

        if ($quoteTransfer->getItems()->count() === 0) {
            return $this->createCartIsEmptyErrorResponse();
        }

        foreach ($this->quoteMappingPlugins as $quoteMappingPlugin) {
            $quoteTransfer = $quoteMappingPlugin->mapRestRequestToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);
        }

        $paymentTransfer = $quoteTransfer->getPayment();

        $quoteResponseTransfer = $this->cartFacade->validateQuote($quoteTransfer);
        if ($quoteResponseTransfer->getIsSuccessful() === false) {
            return $this->createCheckoutResponseTransferFromQuoteErrorTransfer($quoteResponseTransfer);
        }

        $quoteTransfer = $this->restorePaymentInQuote($quoteResponseTransfer->getQuoteTransfer(), $paymentTransfer);

        $checkoutResponseTransfer = $this->checkoutFacade->placeOrder($quoteTransfer);
        if (!$checkoutResponseTransfer->getIsSuccess()) {
            return $checkoutResponseTransfer;
        }

        $quoteResponseTransfer = $this->quoteFacade->deleteQuote($quoteTransfer);
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
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    protected function findCustomerQuote(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): ?QuoteTransfer
    {
        $quoteCriteriaFilterTransfer = (new QuoteCriteriaFilterTransfer())
            ->setCustomerReference($restCheckoutRequestAttributesTransfer->getCart()->getCustomer()->getCustomerReference());

        $quoteTransfer = $this->cartsRestApiFacade
            ->findQuoteByUuid(
                $restCheckoutRequestAttributesTransfer->getCart()->getId(),
                $quoteCriteriaFilterTransfer
            );

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
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function createCartNotFoundErrorResponse(): CheckoutResponseTransfer
    {
        return (new CheckoutResponseTransfer())
            ->setIsSuccess(false)
            ->addError(
                (new CheckoutErrorTransfer())
                    ->setErrorCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setMessage(CheckoutRestApiConfig::ERROR_MESSAGE_CART_NOT_FOUND)
            );
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function createCartIsEmptyErrorResponse(): CheckoutResponseTransfer
    {
        return (new CheckoutResponseTransfer())
            ->setIsSuccess(false)
            ->addError(
                (new CheckoutErrorTransfer())
                    ->setErrorCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setMessage(CheckoutRestApiConfig::ERROR_MESSAGE_CART_IS_EMPTY)
            );
    }
}
