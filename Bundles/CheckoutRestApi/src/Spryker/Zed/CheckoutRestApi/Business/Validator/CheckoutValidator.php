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
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutErrorTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseTransfer;
use Spryker\Shared\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReaderInterface;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartFacadeInterface;

class CheckoutValidator implements CheckoutValidatorInterface
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
     * @var \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataValidatorPluginInterface[]
     */
    protected $checkoutDataValidatorPlugins;

    /**
     * @var \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutValidatorPluginInterface[]
     */
    protected $checkoutValidatorPlugins;

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Business\Checkout\Quote\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataValidatorPluginInterface[] $checkoutDataValidatorPlugins
     * @param \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutValidatorPluginInterface[] $checkoutValidatorPlugins
     */
    public function __construct(
        QuoteReaderInterface $quoteReader,
        CheckoutRestApiToCartFacadeInterface $cartFacade,
        array $checkoutDataValidatorPlugins,
        array $checkoutValidatorPlugins
    ) {
        $this->quoteReader = $quoteReader;
        $this->cartFacade = $cartFacade;
        $this->checkoutDataValidatorPlugins = $checkoutDataValidatorPlugins;
        $this->checkoutValidatorPlugins = $checkoutValidatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseTransfer
     */
    public function validateCheckoutData(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutDataResponseTransfer {
        $quoteTransfer = $this->quoteReader->findCustomerQuoteByUuid($restCheckoutRequestAttributesTransfer);
        $restCheckoutDataResponseTransfer = $this->validateQuoteInCheckoutData($quoteTransfer);

        if (!$restCheckoutDataResponseTransfer->getIsSuccess()) {
            return $restCheckoutDataResponseTransfer;
        }

        $checkoutDataTransfer = (new CheckoutDataTransfer())
            ->fromArray($restCheckoutRequestAttributesTransfer->toArray(), true)
            ->setQuote($quoteTransfer);

        $restCheckoutDataResponseTransfer = $this->executeCheckoutDataValidatorPlugins(
            $checkoutDataTransfer,
            $restCheckoutDataResponseTransfer
        );

        return $this->getRestCheckoutDataResponse($checkoutDataTransfer, $restCheckoutDataResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseTransfer $restCheckoutDataResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseTransfer
     */
    protected function executeCheckoutDataValidatorPlugins(
        CheckoutDataTransfer $checkoutDataTransfer,
        RestCheckoutDataResponseTransfer $restCheckoutDataResponseTransfer
    ): RestCheckoutDataResponseTransfer {
        foreach ($this->checkoutDataValidatorPlugins as $checkoutDataValidatorPlugin) {
            $checkoutResponseTransfer = $checkoutDataValidatorPlugin->validateCheckoutData($checkoutDataTransfer);

            if (!$checkoutResponseTransfer->getIsSuccess()) {
                $restCheckoutDataResponseTransfer = $this->copyCheckoutDataResponseErrors(
                    $checkoutResponseTransfer,
                    $restCheckoutDataResponseTransfer
                );
            }
        }

        return $restCheckoutDataResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseTransfer
     */
    protected function validateQuoteInCheckoutData(?QuoteTransfer $quoteTransfer): RestCheckoutDataResponseTransfer
    {
        if (!$quoteTransfer) {
            return $this->createCheckoutDataCartNotFoundErrorResponse();
        }

        return (new RestCheckoutDataResponseTransfer())
            ->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseTransfer $restCheckoutDataResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseTransfer
     */
    protected function copyCheckoutDataResponseErrors(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        RestCheckoutDataResponseTransfer $restCheckoutDataResponseTransfer
    ): RestCheckoutDataResponseTransfer {
        foreach ($checkoutResponseTransfer->getErrors() as $checkoutErrorTransfer) {
            $restCheckoutDataResponseTransfer->addError(
                (new RestCheckoutErrorTransfer())
                    ->setErrorIdentifier(CheckoutRestApiConfig::ERROR_IDENTIFIER_CHECKOUT_DATA_INVALID)
                    ->setDetail($checkoutErrorTransfer->getMessage())
                    ->setParameters($checkoutErrorTransfer->getParameters())
            );
        }

        return $restCheckoutDataResponseTransfer->setIsSuccess(false);
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseTransfer $restCheckoutDataResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataResponseTransfer
     */
    protected function getRestCheckoutDataResponse(
        CheckoutDataTransfer $checkoutDataTransfer,
        RestCheckoutDataResponseTransfer $restCheckoutDataResponseTransfer
    ): RestCheckoutDataResponseTransfer {
        $restCheckoutDataResponseTransfer->setCheckoutData(
            (new RestCheckoutDataTransfer())->fromArray($checkoutDataTransfer->toArray(), true)
        );

        if ($restCheckoutDataResponseTransfer->getIsSuccess() || $restCheckoutDataResponseTransfer->getErrors()->count()) {
            return $restCheckoutDataResponseTransfer;
        }

        return $restCheckoutDataResponseTransfer
            ->addError(
                (new RestCheckoutErrorTransfer())
                    ->setErrorIdentifier(CheckoutRestApiConfig::ERROR_IDENTIFIER_CHECKOUT_DATA_INVALID)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    public function validateCheckout(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutResponseTransfer {
        $quoteTransfer = $this->quoteReader->findCustomerQuoteByUuid($restCheckoutRequestAttributesTransfer);
        $restCheckoutResponseTransfer = $this->validateQuoteInCheckout($quoteTransfer);

        if (!$restCheckoutResponseTransfer->getIsSuccess()) {
            return $restCheckoutResponseTransfer;
        }

        $checkoutDataTransfer = (new CheckoutDataTransfer())
            ->fromArray($restCheckoutRequestAttributesTransfer->toArray(), true)
            ->setQuote($quoteTransfer);

        $restCheckoutResponseTransfer = $this->executeCheckoutValidatorPlugins(
            $checkoutDataTransfer,
            $restCheckoutResponseTransfer
        );

        return $this->getRestCheckoutResponse($checkoutDataTransfer, $restCheckoutResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutResponseTransfer $restCheckoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    protected function executeCheckoutValidatorPlugins(
        CheckoutDataTransfer $checkoutDataTransfer,
        RestCheckoutResponseTransfer $restCheckoutResponseTransfer
    ): RestCheckoutResponseTransfer {
        foreach ($this->checkoutValidatorPlugins as $checkoutValidatorPlugin) {
            $checkoutResponseTransfer = $checkoutValidatorPlugin->validateCheckout($checkoutDataTransfer);

            if (!$checkoutResponseTransfer->getIsSuccess()) {
                $restCheckoutResponseTransfer = $this->copyCheckoutResponseErrors(
                    $checkoutResponseTransfer,
                    $restCheckoutResponseTransfer
                );
            }
        }

        return $restCheckoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutResponseTransfer $restCheckoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    protected function copyCheckoutResponseErrors(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        RestCheckoutResponseTransfer $restCheckoutResponseTransfer
    ): RestCheckoutResponseTransfer {
        foreach ($checkoutResponseTransfer->getErrors() as $checkoutErrorTransfer) {
            $restCheckoutResponseTransfer->addError(
                (new RestCheckoutErrorTransfer())
                    ->setErrorIdentifier(CheckoutRestApiConfig::ERROR_IDENTIFIER_ORDER_NOT_PLACED)
                    ->setDetail($checkoutErrorTransfer->getMessage())
                    ->setParameters($checkoutErrorTransfer->getParameters())
            );
        }

        return $restCheckoutResponseTransfer->setIsSuccess(false);
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutResponseTransfer $restCheckoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    protected function getRestCheckoutResponse(
        CheckoutDataTransfer $checkoutDataTransfer,
        RestCheckoutResponseTransfer $restCheckoutResponseTransfer
    ): RestCheckoutResponseTransfer {
        $restCheckoutResponseTransfer->setCheckoutData($checkoutDataTransfer);

        if ($restCheckoutResponseTransfer->getIsSuccess() || $restCheckoutResponseTransfer->getErrors()->count()) {
            return $restCheckoutResponseTransfer;
        }

        return $restCheckoutResponseTransfer
            ->addError(
                (new RestCheckoutErrorTransfer())
                    ->setErrorIdentifier(CheckoutRestApiConfig::ERROR_IDENTIFIER_ORDER_NOT_PLACED)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutResponseTransfer
     */
    protected function validateQuoteInCheckout(?QuoteTransfer $quoteTransfer): RestCheckoutResponseTransfer
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
}
