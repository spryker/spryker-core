<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutErrorTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseTransfer;
use Spryker\Shared\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Zed\CheckoutRestApi\Business\Validator\CheckoutValidatorInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCalculationFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCheckoutFacadeInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToQuoteFacadeInterface;

class PlaceOrderProcessor implements PlaceOrderProcessorInterface
{
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
     * @var \Spryker\Zed\CheckoutRestApi\Business\Validator\CheckoutValidatorInterface
     */
    protected $checkoutValidator;

    /**
     * @var \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface[]
     */
    protected $quoteMapperPlugins;

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCheckoutFacadeInterface $checkoutFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCalculationFacadeInterface $calculationFacade
     * @param \Spryker\Zed\CheckoutRestApi\Business\Validator\CheckoutValidatorInterface $checkoutValidator
     * @param \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface[] $quoteMapperPlugins
     */
    public function __construct(
        CheckoutRestApiToCheckoutFacadeInterface $checkoutFacade,
        CheckoutRestApiToQuoteFacadeInterface $quoteFacade,
        CheckoutRestApiToCalculationFacadeInterface $calculationFacade,
        CheckoutValidatorInterface $checkoutValidator,
        array $quoteMapperPlugins
    ) {
        $this->checkoutFacade = $checkoutFacade;
        $this->quoteFacade = $quoteFacade;
        $this->calculationFacade = $calculationFacade;
        $this->checkoutValidator = $checkoutValidator;
        $this->quoteMapperPlugins = $quoteMapperPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    public function placeOrder(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestCheckoutResponseTransfer
    {
        $restCheckoutResponseTransfer = $this->checkoutValidator->validateCheckout($restCheckoutRequestAttributesTransfer);

        if (!$restCheckoutResponseTransfer->getIsSuccess()) {
            return $restCheckoutResponseTransfer;
        }

        $quoteTransfer = $restCheckoutResponseTransfer->getCheckoutData()->getQuote();

        $quoteTransfer = $this->executeQuoteMapperPlugins($restCheckoutRequestAttributesTransfer, $quoteTransfer);
        $quoteTransfer = $this->calculationFacade->recalculateQuote($quoteTransfer);

        return $this->placeOrderWithCartRemoval($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    protected function placeOrderWithCartRemoval(QuoteTransfer $quoteTransfer): RestCheckoutResponseTransfer
    {
        $checkoutResponseTransfer = $this->checkoutFacade->placeOrder($quoteTransfer);

        if (!$checkoutResponseTransfer->getIsSuccess()) {
            return $this->createPlaceOrderErrorResponse($checkoutResponseTransfer);
        }

        $quoteResponseTransfer = $this->quoteFacade->deleteQuote($quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->createRestCheckoutResponseError(
                $quoteResponseTransfer,
                CheckoutRestApiConfig::ERROR_IDENTIFIER_UNABLE_TO_DELETE_CART
            );
        }

        return $this->createRestCheckoutResponseTransfer($checkoutResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executeQuoteMapperPlugins(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        foreach ($this->quoteMapperPlugins as $quoteMapperPlugin) {
            $quoteTransfer = $quoteMapperPlugin->map($restCheckoutRequestAttributesTransfer, $quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     * @param string $errorIdentifier
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    protected function createRestCheckoutResponseError(
        QuoteResponseTransfer $quoteResponseTransfer,
        string $errorIdentifier
    ): RestCheckoutResponseTransfer {
        $restCheckoutResponseTransfer = (new RestCheckoutResponseTransfer())
            ->setIsSuccess(false);

        if (!$quoteResponseTransfer->getErrors()->count()) {
            return $restCheckoutResponseTransfer
                ->addError(
                    (new RestCheckoutErrorTransfer())
                        ->setErrorIdentifier($errorIdentifier)
                );
        }

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
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    protected function createPlaceOrderErrorResponse(CheckoutResponseTransfer $checkoutResponseTransfer): RestCheckoutResponseTransfer
    {
        $restCheckoutResponseTransfer = (new RestCheckoutResponseTransfer())
            ->setIsSuccess(false);

        if (!$checkoutResponseTransfer->getErrors()->count()) {
            return $restCheckoutResponseTransfer
                ->addError(
                    (new RestCheckoutErrorTransfer())
                        ->setErrorIdentifier(CheckoutRestApiConfig::ERROR_IDENTIFIER_ORDER_NOT_PLACED)
                );
        }

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
