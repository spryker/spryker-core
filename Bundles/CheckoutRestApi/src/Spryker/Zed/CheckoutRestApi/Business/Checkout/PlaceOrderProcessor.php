<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\RestCheckoutErrorTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseTransfer;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReaderInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCalculationFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCheckoutFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToQuoteFacadeInterface;
use Symfony\Component\HttpFoundation\Response;

class PlaceOrderProcessor implements PlaceOrderProcessorInterface
{
    /**
     * @var \Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReaderInterface
     */
    protected $quoteReader;

    /**
     * @var \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCheckoutFacadeInterface
     */
    protected $checkoutFacade;

    /**
     * @var \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCalculationFacadeInterface
     */
    protected $calculationFacade;

    /**
     * @var \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface[]
     */
    protected $quoteMappingPlugins;

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCheckoutFacadeInterface $checkoutFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCalculationFacadeInterface $calculationFacade
     * @param \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface[] $quoteMappingPlugins
     */
    public function __construct(
        QuoteReaderInterface $quoteReader,
        CheckoutRestApiToCartFacadeInterface $cartFacade,
        CheckoutRestApiToCheckoutFacadeInterface $checkoutFacade,
        CheckoutRestApiToQuoteFacadeInterface $quoteFacade,
        CheckoutRestApiToCalculationFacadeInterface $calculationFacade,
        array $quoteMappingPlugins
    ) {
        $this->quoteReader = $quoteReader;
        $this->cartFacade = $cartFacade;
        $this->checkoutFacade = $checkoutFacade;
        $this->quoteFacade = $quoteFacade;
        $this->calculationFacade = $calculationFacade;
        $this->quoteMappingPlugins = $quoteMappingPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    public function placeOrder(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestCheckoutResponseTransfer
    {
        $quoteTransfer = $this->quoteReader->findCustomerQuote($restCheckoutRequestAttributesTransfer);

        if (!$quoteTransfer) {
            return $this->createCartNotFoundErrorResponse();
        }

        if (!count($quoteTransfer->getItems())) {
            return $this->createCartIsEmptyErrorResponse();
        }

        $quoteResponseTransfer = $this->cartFacade->validateQuote($quoteTransfer);

        if ($quoteResponseTransfer->getIsSuccessful() === false) {
            return $this->createCheckoutResponseTransferFromQuoteErrorTransfer($quoteResponseTransfer);
        }

        foreach ($this->quoteMappingPlugins as $quoteMappingPlugin) {
            $quoteTransfer = $quoteMappingPlugin->map($restCheckoutRequestAttributesTransfer, $quoteTransfer);
        }

        $quoteTransfer = $this->calculationFacade->recalculateQuote($quoteTransfer);

        $checkoutResponseTransfer = $this->checkoutFacade->placeOrder($quoteTransfer);
        if (!$checkoutResponseTransfer->getIsSuccess()) {
            return $this->createRestCheckoutResponseWithErrorFromCheckoutResponseTransfer($checkoutResponseTransfer);
        }

        $quoteResponseTransfer = $this->quoteFacade->deleteQuote($quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->createCheckoutResponseTransferFromQuoteErrorTransfer($quoteResponseTransfer);
        }

        return (new RestCheckoutResponseTransfer())
            ->setIsSuccess(true)
            ->setOrderReference($checkoutResponseTransfer->getSaveOrder()->getOrderReference());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    protected function createCheckoutResponseTransferFromQuoteErrorTransfer(QuoteResponseTransfer $quoteResponseTransfer): RestCheckoutResponseTransfer
    {
        if ($quoteResponseTransfer->getErrors()->count() === 0) {
            return (new RestCheckoutResponseTransfer())
                ->setIsSuccess(false)
                ->addError(
                    (new RestCheckoutErrorTransfer())
                        ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                        ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_CHECKOUT_DATA_INVALID)
                        ->setDetail(CheckoutRestApiConfig::RESPONSE_DETAILS_CHECKOUT_DATA_INVALID)
                );
        }

        $restCheckoutResponseTransfer = (new RestCheckoutResponseTransfer())
            ->setIsSuccess(false);
        foreach ($quoteResponseTransfer->getErrors() as $quoteErrorTransfer) {
            $restCheckoutResponseTransfer->addError(
                (new RestCheckoutErrorTransfer())
                    ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_CHECKOUT_DATA_INVALID)
                    ->setDetail($quoteErrorTransfer->getMessage())
            );
        }

        return $restCheckoutResponseTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    protected function createCartNotFoundErrorResponse(): RestCheckoutResponseTransfer
    {
        return (new RestCheckoutResponseTransfer())
            ->setIsSuccess(false)
            ->addError(
                (new RestCheckoutErrorTransfer())
                    ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_CART_NOT_FOUND)
                    ->setDetail(CheckoutRestApiConfig::RESPONSE_DETAILS_CART_NOT_FOUND)
            );
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    protected function createCartIsEmptyErrorResponse(): RestCheckoutResponseTransfer
    {
        return (new RestCheckoutResponseTransfer())
            ->setIsSuccess(false)
            ->addError(
                (new RestCheckoutErrorTransfer())
                    ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_CART_IS_EMPTY)
                    ->setDetail(CheckoutRestApiConfig::RESPONSE_DETAILS_CART_IS_EMPTY)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    protected function createRestCheckoutResponseWithErrorFromCheckoutResponseTransfer(CheckoutResponseTransfer $checkoutResponseTransfer): RestCheckoutResponseTransfer
    {
        if ($checkoutResponseTransfer->getErrors()->count() === 0) {
            return (new RestCheckoutResponseTransfer())
                ->setIsSuccess(false)
                ->addError(
                    (new RestCheckoutErrorTransfer())
                        ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                        ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_ORDER_NOT_PLACED)
                        ->setDetail(CheckoutRestApiConfig::RESPONSE_DETAILS_ORDER_NOT_PLACED)
                );
        }
        $restCheckoutResponseTransfer = (new RestCheckoutResponseTransfer())
            ->setIsSuccess(false);
        foreach ($checkoutResponseTransfer->getErrors() as $errorTransfer) {
            $restCheckoutResponseTransfer->addError(
                (new RestCheckoutErrorTransfer())
                    ->setStatus($errorTransfer->getErrorCode())
                    ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_ORDER_NOT_PLACED)
                    ->setDetail($errorTransfer->getMessage())
                    ->setParameters($errorTransfer->getParameters())
            );
        }

        return $restCheckoutResponseTransfer;
    }
}
