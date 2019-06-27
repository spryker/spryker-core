<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Checkout;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutErrorTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseTransfer;
use Spryker\Shared\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReaderInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCalculationFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCheckoutFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToQuoteFacadeInterface;

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
    protected $quoteMapperPlugins;

    /**
     * @var \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataValidatorPluginInterface[]
     */
    protected $checkoutDataValidatorPlugins;

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCheckoutFacadeInterface $checkoutFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCalculationFacadeInterface $calculationFacade
     * @param \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface[] $quoteMapperPlugins
     * @param \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataValidatorPluginInterface[] $checkoutDataValidatorPlugins
     */
    public function __construct(
        QuoteReaderInterface $quoteReader,
        CheckoutRestApiToCartFacadeInterface $cartFacade,
        CheckoutRestApiToCheckoutFacadeInterface $checkoutFacade,
        CheckoutRestApiToQuoteFacadeInterface $quoteFacade,
        CheckoutRestApiToCalculationFacadeInterface $calculationFacade,
        array $quoteMapperPlugins,
        array $checkoutDataValidatorPlugins
    ) {
        $this->quoteReader = $quoteReader;
        $this->cartFacade = $cartFacade;
        $this->checkoutFacade = $checkoutFacade;
        $this->quoteFacade = $quoteFacade;
        $this->calculationFacade = $calculationFacade;
        $this->quoteMapperPlugins = $quoteMapperPlugins;
        $this->checkoutDataValidatorPlugins = $checkoutDataValidatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    public function placeOrder(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestCheckoutResponseTransfer
    {
        $checkoutResponseTransfer = $this->validateCheckoutData($restCheckoutRequestAttributesTransfer);
        if (!$checkoutResponseTransfer->getIsSuccess()) {
            return $this->createPlaceOrderErrorResponse($checkoutResponseTransfer);
        }

        $quoteTransfer = $this->quoteReader->findCustomerQuoteByUuid($restCheckoutRequestAttributesTransfer);

        $restCheckoutResponseTransfer = $this->validateQuoteTransfer($quoteTransfer);
        if ($restCheckoutResponseTransfer !== null) {
            return $restCheckoutResponseTransfer;
        }

        $quoteTransfer = $this->mapRestCheckoutRequestAttributesToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $quoteTransfer = $this->recalculateQuote($quoteTransfer);

        $checkoutResponseTransfer = $this->executePlaceOrder($quoteTransfer);
        if (!$checkoutResponseTransfer->getIsSuccess()) {
            return $this->createPlaceOrderErrorResponse($checkoutResponseTransfer);
        }

        $quoteResponseTransfer = $this->deleteQuote($quoteTransfer);
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->createQuoteResponseError(
                $quoteResponseTransfer,
                CheckoutRestApiConfig::ERROR_IDENTIFIER_UNABLE_TO_DELETE_CART
            );
        }

        return $this->createRestCheckoutResponseTransfer($checkoutResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer|null
     */
    protected function validateQuoteTransfer(?QuoteTransfer $quoteTransfer): ?RestCheckoutResponseTransfer
    {
        if (!$quoteTransfer) {
            return $this->createCartNotFoundErrorResponse();
        }

        if (!count($quoteTransfer->getItems())) {
            return $this->createCartIsEmptyErrorResponse();
        }

        $quoteResponseTransfer = $this->cartFacade->validateQuote($quoteTransfer);

        if ($quoteResponseTransfer->getIsSuccessful() === false) {
            return $this->createQuoteResponseError(
                $quoteResponseTransfer,
                CheckoutRestApiConfig::ERROR_IDENTIFIER_CHECKOUT_DATA_INVALID
            );
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function validateCheckoutData(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): CheckoutResponseTransfer
    {
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(true);
        $checkoutDataTransfer = (new CheckoutDataTransfer())
            ->fromArray($restCheckoutRequestAttributesTransfer->toArray(), true);

        foreach ($this->checkoutDataValidatorPlugins as $checkoutDataValidatorPlugin) {
            $validatedCheckoutData = $checkoutDataValidatorPlugin->validateCheckoutData($checkoutDataTransfer);
            if (!$validatedCheckoutData->getIsSuccess()) {
                $checkoutResponseTransfer = $this->appendCheckoutResponseErrors(
                    $validatedCheckoutData,
                    $checkoutResponseTransfer
                );
            }
        }

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $validatedCheckoutData
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function appendCheckoutResponseErrors(
        CheckoutResponseTransfer $validatedCheckoutData,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): CheckoutResponseTransfer {
        foreach ($validatedCheckoutData->getErrors() as $checkoutErrorTransfer) {
            $checkoutResponseTransfer->getErrors()->append($checkoutErrorTransfer);
        }

        return $checkoutResponseTransfer->setIsSuccess(false);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mapRestCheckoutRequestAttributesToQuote(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        foreach ($this->quoteMapperPlugins as $quoteMapperPlugin) {
            $quoteTransfer = $quoteMapperPlugin->map($restCheckoutRequestAttributesTransfer, $quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function recalculateQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->calculationFacade->recalculateQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function executePlaceOrder(QuoteTransfer $quoteTransfer): CheckoutResponseTransfer
    {
        return $this->checkoutFacade->placeOrder($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function deleteQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->quoteFacade->deleteQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     * @param string $errorIdentifier
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    protected function createQuoteResponseError(
        QuoteResponseTransfer $quoteResponseTransfer,
        string $errorIdentifier
    ): RestCheckoutResponseTransfer {
        if ($quoteResponseTransfer->getErrors()->count() === 0) {
            return (new RestCheckoutResponseTransfer())
                ->setIsSuccess(false)
                ->addError(
                    (new RestCheckoutErrorTransfer())
                        ->setErrorIdentifier($errorIdentifier)
                );
        }

        $restCheckoutResponseTransfer = (new RestCheckoutResponseTransfer())
            ->setIsSuccess(false);
        foreach ($quoteResponseTransfer->getErrors() as $quoteErrorTransfer) {
            $restCheckoutResponseTransfer->addError(
                (new RestCheckoutErrorTransfer())
                    ->setErrorIdentifier($errorIdentifier)
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
                    ->setErrorIdentifier(CheckoutRestApiConfig::ERROR_IDENTIFIER_CART_NOT_FOUND)
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
                    ->setErrorIdentifier(CheckoutRestApiConfig::ERROR_IDENTIFIER_CART_IS_EMPTY)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    protected function createPlaceOrderErrorResponse(CheckoutResponseTransfer $checkoutResponseTransfer): RestCheckoutResponseTransfer
    {
        if ($checkoutResponseTransfer->getErrors()->count() === 0) {
            return (new RestCheckoutResponseTransfer())
                ->setIsSuccess(false)
                ->addError(
                    (new RestCheckoutErrorTransfer())
                        ->setErrorIdentifier(CheckoutRestApiConfig::ERROR_IDENTIFIER_ORDER_NOT_PLACED)
                );
        }
        $restCheckoutResponseTransfer = (new RestCheckoutResponseTransfer())
            ->setIsSuccess(false);
        foreach ($checkoutResponseTransfer->getErrors() as $errorTransfer) {
            $restCheckoutResponseTransfer->addError(
                (new RestCheckoutErrorTransfer())
                    ->setErrorIdentifier(CheckoutRestApiConfig::ERROR_IDENTIFIER_ORDER_NOT_PLACED)
                    ->setDetail($errorTransfer->getMessage())
                    ->setParameters($errorTransfer->getParameters())
            );
        }

        return $restCheckoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    protected function createRestCheckoutResponseTransfer(CheckoutResponseTransfer $checkoutResponseTransfer): RestCheckoutResponseTransfer
    {
        return (new RestCheckoutResponseTransfer())
            ->setIsSuccess(true)
            ->setRedirectUrl($checkoutResponseTransfer->getRedirectUrl())
            ->setIsExternalRedirect($checkoutResponseTransfer->getIsExternalRedirect())
            ->setOrderReference($checkoutResponseTransfer->getSaveOrder()->getOrderReference())
            ->setCheckoutResponse($checkoutResponseTransfer);
    }
}
