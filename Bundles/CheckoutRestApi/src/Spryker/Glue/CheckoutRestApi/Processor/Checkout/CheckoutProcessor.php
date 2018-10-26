<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\Checkout;

use ArrayObject;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCheckoutClientInterface;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToZedRequestClientInterface;
use Spryker\Glue\CheckoutRestApi\Processor\Quote\QuoteProcessorInterface;
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
     * @var \Spryker\Glue\CheckoutRestApi\Processor\Quote\QuoteProcessorInterface
     */
    protected $quoteProcessor;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCheckoutClientInterface
     */
    protected $checkoutClient;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CheckoutRestApi\Processor\Quote\QuoteProcessorInterface $quoteProcessor
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCheckoutClientInterface $checkoutClient
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToZedRequestClientInterface $zedRequestClient
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        QuoteProcessorInterface $quoteProcessor,
        CheckoutRestApiToCheckoutClientInterface $checkoutClient,
        CheckoutRestApiToZedRequestClientInterface $zedRequestClient,
        CheckoutRestApiToGlossaryStorageClientInterface $glossaryStorageClient
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->quoteProcessor = $quoteProcessor;
        $this->checkoutClient = $checkoutClient;
        $this->zedRequestClient = $zedRequestClient;
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function placeOrder(RestRequestInterface $restRequest, RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestResponseInterface
    {
        $quoteTransfer = $this->quoteProcessor->getCustomerQuote($restCheckoutRequestAttributesTransfer);
        if ($quoteTransfer === null) {
            return $this->createQuoteNotFoundErrorResponse();
        }

        $quoteResponseTransfer = $this->quoteProcessor->validateQuote();
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->createErrorMessagesResponse($this->zedRequestClient->getLastResponseErrorMessages());
        }

        $quoteTransfer = $this->quoteProcessor->updateQuoteWithDataFromRequest(
            $quoteTransfer,
            $restCheckoutRequestAttributesTransfer,
            $restRequest
        );

        $checkoutResponseTransfer = $this->checkoutClient->placeOrder($quoteTransfer);
        if (!$checkoutResponseTransfer->getIsSuccess()) {
            return $this->createCheckoutErrorResponse($checkoutResponseTransfer->getErrors(), $restRequest->getMetadata()->getLocale());
        }

        $this->quoteProcessor->clearQuote();

        return $this->createResponse($checkoutResponseTransfer->getSaveOrder()->getOrderReference());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createQuoteNotFoundErrorResponse(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $restResponse->addError(
            (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_QUOTE_NOT_FOUND)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(CheckoutRestApiConfig::EXCEPTION_MESSAGE_QUOTE_NOT_FOUND)
        );

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createDataInvalidErrorResponse(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $restResponse->addError(
            (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_DATA_INVALID)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(CheckoutRestApiConfig::EXCEPTION_MESSAGE_DATA_INVALID)
        );

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer[]|\ArrayObject $errors
     * @param string $currentLocale
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createCheckoutErrorResponse(ArrayObject $errors, string $currentLocale): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($errors as $checkoutErrorTransfer) {
            $restErrorMessageTransfer = (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_ORDER_NOT_PLACED)
                ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->setDetail($this->translateCheckoutErrorMessage($checkoutErrorTransfer, $currentLocale));

            $restResponse->addError($restErrorMessageTransfer);
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $checkoutErrorTransfer
     * @param string $currentLocale
     *
     * @return string
     */
    protected function translateCheckoutErrorMessage(CheckoutErrorTransfer $checkoutErrorTransfer, string $currentLocale): string
    {
        $checkoutErrorMessage = $checkoutErrorTransfer->getMessage();

        return $this->glossaryStorageClient->translate(
            $checkoutErrorMessage,
            $currentLocale,
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
    protected function createResponse(string $orderReference): RestResponseInterface
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
