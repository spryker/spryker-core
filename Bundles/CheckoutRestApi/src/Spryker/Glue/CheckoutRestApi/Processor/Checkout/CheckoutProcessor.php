<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\Checkout;

use ArrayObject;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCheckoutClientInterface;
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
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CheckoutRestApi\Processor\Quote\QuoteProcessorInterface $quoteProcessor
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCheckoutClientInterface $checkoutClient
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        QuoteProcessorInterface $quoteProcessor,
        CheckoutRestApiToCheckoutClientInterface $checkoutClient
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->quoteProcessor = $quoteProcessor;
        $this->checkoutClient = $checkoutClient;
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
            return $this->createQuoteNotFoundError();
        }

        if (!$this->quoteProcessor->validateQuote()->getIsSuccessful()) {
            return $this->createDataInvalidError();
        }

        $quoteTransfer = $this->quoteProcessor->updateQuoteWithDataFromRequest(
            $quoteTransfer,
            $restCheckoutRequestAttributesTransfer,
            $restRequest
        );

        $checkoutResponseTransfer = $this->checkoutClient->placeOrder($quoteTransfer);
        if (!$checkoutResponseTransfer->getIsSuccess()) {
            return $this->returnWithError($checkoutResponseTransfer->getErrors());
        }

        return $this->createResponse($checkoutResponseTransfer->getSaveOrder()->getOrderReference());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createQuoteNotFoundError(): RestResponseInterface
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
    protected function createDataInvalidError(): RestResponseInterface
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
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function returnWithError(ArrayObject $errors): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($errors as $checkoutErrorTransfer) {
            $restErrorMessageTransfer = (new RestErrorMessageTransfer())
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_ORDER_NOT_PLACED)
                ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->setDetail($checkoutErrorTransfer->getMessage());

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
