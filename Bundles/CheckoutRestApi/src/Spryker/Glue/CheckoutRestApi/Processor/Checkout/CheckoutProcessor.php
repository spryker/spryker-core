<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\Checkout;

use ArrayObject;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Client\CheckoutRestApi\CheckoutRestApiClientInterface;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartClientInterface;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartsRestApiClientInterface;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\CheckoutRestApi\Processor\Quote\QuoteMergerInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class CheckoutProcessor implements CheckoutProcessorInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Processor\Quote\QuoteMergerInterface
     */
    protected $quoteMerger;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @var \Spryker\Client\CheckoutRestApi\CheckoutRestApiClientInterface
     */
    protected $checkoutRestApiClient;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartsRestApiClientInterface
     */
    protected $cartsRestApiClient;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartClientInterface
     */
    protected $cartClient;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CheckoutRestApi\Processor\Quote\QuoteMergerInterface $quoteMerger
     * @param \Spryker\Client\CheckoutRestApi\CheckoutRestApiClientInterface $checkoutRestApiClient
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartsRestApiClientInterface $cartsRestApiClient
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartClientInterface $cartClient
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        QuoteMergerInterface $quoteMerger,
        CheckoutRestApiClientInterface $checkoutRestApiClient,
        CheckoutRestApiToGlossaryStorageClientInterface $glossaryStorageClient,
        CheckoutRestApiToCartsRestApiClientInterface $cartsRestApiClient,
        CheckoutRestApiToCartClientInterface $cartClient
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->quoteMerger = $quoteMerger;
        $this->checkoutRestApiClient = $checkoutRestApiClient;
        $this->glossaryStorageClient = $glossaryStorageClient;
        $this->cartsRestApiClient = $cartsRestApiClient;
        $this->cartClient = $cartClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function placeOrder(RestRequestInterface $restRequest, RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestResponseInterface
    {
        $quoteTransfer = $this->cartsRestApiClient->findQuoteByUuid(
            $restCheckoutRequestAttributesTransfer->getCart()->getId(),
            new QuoteCriteriaFilterTransfer()
        );

        if ($quoteTransfer === null) {
            return $this->createCartNotFoundErrorResponse();
        }

        if ($quoteTransfer->getItems()->count() === 0) {
            return $this->createCartIsEmptyErrorResponse();
        }

        $quoteTransfer = $this->quoteMerger->updateQuoteWithDataFromRequest(
            $quoteTransfer,
            $restCheckoutRequestAttributesTransfer,
            $restRequest
        );

        $checkoutResponseTransfer = $this->checkoutRestApiClient->placeOrder($quoteTransfer);
        if (!$checkoutResponseTransfer->getIsSuccess()) {
            return $this->createPlaceOrderFailedErrorResponse($checkoutResponseTransfer->getErrors(), $restRequest->getMetadata()->getLocale());
        }

        $this->cartClient->clearQuote();

        return $this->createOrderPlacedResponse($checkoutResponseTransfer->getSaveOrder()->getOrderReference());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createCartNotFoundErrorResponse(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $restResponse->addError(
            (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_CART_NOT_FOUND)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(CheckoutRestApiConfig::RESPONSE_DETAILS_CART_NOT_FOUND)
        );

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createCartIsEmptyErrorResponse(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $restResponse->addError(
            (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_CART_IS_EMPTY)
                ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->setDetail(CheckoutRestApiConfig::RESPONSE_DETAIL_CART_IS_EMPTY)
        );

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createCheckoutDataInvalidErrorResponse(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $restResponse->addError(
            (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_CHECKOUT_DATA_INVALID)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(CheckoutRestApiConfig::RESPONSE_DETAILS_CHECKOUT_DATA_INVALID)
        );

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer[]|\ArrayObject $errors
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createPlaceOrderFailedErrorResponse(ArrayObject $errors, string $localeName): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($errors as $checkoutErrorTransfer) {
            $restErrorMessageTransfer = (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_ORDER_NOT_PLACED)
                ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->setDetail($this->translateCheckoutErrorMessage($checkoutErrorTransfer, $localeName));

            $restResponse->addError($restErrorMessageTransfer);
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $checkoutErrorTransfer
     * @param string $localeName
     *
     * @return string
     */
    protected function translateCheckoutErrorMessage(CheckoutErrorTransfer $checkoutErrorTransfer, string $localeName): string
    {
        $checkoutErrorMessage = $checkoutErrorTransfer->getMessage();

        return $this->glossaryStorageClient->translate(
            $checkoutErrorMessage,
            $localeName,
            $checkoutErrorTransfer->getParameters()
        ) ?: $checkoutErrorMessage;
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer[] $messageTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createErrorMessagesResponse(array $messageTransfers): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($messageTransfers as $messageTransfer) {
            $restErrorMessageTransfer = (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_ORDER_NOT_PLACED)
                ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->setDetail($messageTransfer->getValue());

            $restResponse->addError($restErrorMessageTransfer);
        }

        return $restResponse;
    }

    /**
     * @param string $orderReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createOrderPlacedResponse(string $orderReference): RestResponseInterface
    {
        $restResource = $this->restResourceBuilder->createRestResource(
            CheckoutRestApiConfig::RESOURCE_CHECKOUT,
            null,
            (new RestCheckoutResponseAttributesTransfer())->setOrderReference($orderReference)
        );
        $restResponse = $this->restResourceBuilder->createRestResponse();

        return $restResponse->addResource($restResource);
    }
}
