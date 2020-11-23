<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Validator;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutDataResponseTransfer;
use Generated\Shared\Transfer\RestCheckoutErrorTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseTransfer;
use Spryker\Shared\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartFacadeInterface;

class CheckoutValidator implements CheckoutValidatorInterface
{
    /**
     * @var \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataValidatorPluginInterface[]
     */
    protected $checkoutDataValidatorPlugins;

    /**
     * @var \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutValidatorPluginInterface[]
     */
    protected $checkoutValidatorPlugins;

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataValidatorPluginInterface[] $checkoutDataValidatorPlugins
     * @param \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutValidatorPluginInterface[] $checkoutValidatorPlugins
     */
    public function __construct(
        CheckoutRestApiToCartFacadeInterface $cartFacade,
        array $checkoutDataValidatorPlugins,
        array $checkoutValidatorPlugins
    ) {
        $this->cartFacade = $cartFacade;
        $this->checkoutDataValidatorPlugins = $checkoutDataValidatorPlugins;
        $this->checkoutValidatorPlugins = $checkoutValidatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateCheckoutData(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): CheckoutResponseTransfer {
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(true);
        $checkoutDataTransfer = (new CheckoutDataTransfer())
            ->fromArray($restCheckoutRequestAttributesTransfer->toArray(), true)
            ->setQuote($quoteTransfer);

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
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateCheckout(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): CheckoutResponseTransfer {
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(true);
        $checkoutDataTransfer = (new CheckoutDataTransfer())
            ->fromArray($restCheckoutRequestAttributesTransfer->toArray(), true)
            ->setQuote($quoteTransfer);

        foreach ($this->checkoutValidatorPlugins as $checkoutValidatorPlugin) {
            $validatedCheckout = $checkoutValidatorPlugin->validateCheckout($checkoutDataTransfer);

            if (!$validatedCheckout->getIsSuccess()) {
                $checkoutResponseTransfer = $this->appendCheckoutResponseErrors(
                    $validatedCheckout,
                    $checkoutResponseTransfer
                );
            }
        }

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    public function validateQuoteInCheckout(?QuoteTransfer $quoteTransfer): RestCheckoutResponseTransfer
    {
        if (!$quoteTransfer) {
            return $this->createCheckoutCartNotFoundErrorResponse();
        }

        if (!$quoteTransfer->getItems()->count()) {
            return $this->createCartIsEmptyErrorResponse();
        }

        $quoteResponseTransfer = $this->cartFacade->validateQuote($quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->createQuoteResponseError(
                $quoteResponseTransfer,
                CheckoutRestApiConfig::ERROR_IDENTIFIER_CHECKOUT_DATA_INVALID
            );
        }

        return (new RestCheckoutResponseTransfer())
            ->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseTransfer
     */
    public function validateQuoteInCheckoutData(?QuoteTransfer $quoteTransfer): RestCheckoutDataResponseTransfer
    {
        if (!$quoteTransfer) {
            return $this->createCheckoutDataCartNotFoundErrorResponse();
        }

        return (new RestCheckoutDataResponseTransfer())
            ->setIsSuccess(true);
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    protected function createCheckoutCartNotFoundErrorResponse(): RestCheckoutResponseTransfer
    {
        return (new RestCheckoutResponseTransfer())
            ->setIsSuccess(false)
            ->addError(
                (new RestCheckoutErrorTransfer())
                    ->setErrorIdentifier(CheckoutRestApiConfig::ERROR_IDENTIFIER_CART_NOT_FOUND)
            );
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseTransfer
     */
    protected function createCheckoutDataCartNotFoundErrorResponse(): RestCheckoutDataResponseTransfer
    {
        return (new RestCheckoutDataResponseTransfer())
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
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     * @param string $errorIdentifier
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    protected function createQuoteResponseError(
        QuoteResponseTransfer $quoteResponseTransfer,
        string $errorIdentifier
    ): RestCheckoutResponseTransfer {
        $restCheckoutResponseTransfer = (new RestCheckoutResponseTransfer())
            ->setIsSuccess(false);

        if (!$quoteResponseTransfer->getErrors()->count()) {
            return $restCheckoutResponseTransfer->addError(
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
}
