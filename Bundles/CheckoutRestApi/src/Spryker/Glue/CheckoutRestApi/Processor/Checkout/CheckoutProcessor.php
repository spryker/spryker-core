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
use Generated\Shared\Transfer\RestCustomerTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Client\CheckoutRestApi\CheckoutRestApiClientInterface;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface;
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
     * @var \Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface
     */
    protected $checkoutDataMapper;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @var \Spryker\Client\CheckoutRestApi\CheckoutRestApiClientInterface
     */
    protected $checkoutRestApiClient;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface $checkoutDataMapper
     * @param \Spryker\Client\CheckoutRestApi\CheckoutRestApiClientInterface $checkoutRestApiClient
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CheckoutDataMapperInterface $checkoutDataMapper,
        CheckoutRestApiClientInterface $checkoutRestApiClient,
        CheckoutRestApiToGlossaryStorageClientInterface $glossaryStorageClient
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->checkoutDataMapper = $checkoutDataMapper;
        $this->checkoutRestApiClient = $checkoutRestApiClient;
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
        $restCheckoutRequestAttributesTransfer->getCart()
            ->setCustomer(
                $this->getCustomerTransferFromRequest($restRequest, $restCheckoutRequestAttributesTransfer)
            );
        $checkoutResponseTransfer = $this->checkoutRestApiClient->placeOrder($restCheckoutRequestAttributesTransfer);
        if (!$checkoutResponseTransfer->getIsSuccess()) {
            return $this->createPlaceOrderFailedErrorResponse($checkoutResponseTransfer->getErrors(), $restRequest->getMetadata()->getLocale());
        }

        return $this->createOrderPlacedResponse($checkoutResponseTransfer->getSaveOrder()->getOrderReference());
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCustomerTransfer
     */
    protected function getCustomerTransferFromRequest(
        RestRequestInterface $restRequest,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCustomerTransfer {
        $restCustomerTransfer = new RestCustomerTransfer();
        if ($restRequest->getUser()->getSurrogateIdentifier()) {
            return $restCustomerTransfer->setCustomerReference($restRequest->getUser()->getNaturalIdentifier())
                ->setIdCustomer((int)$restRequest->getUser()->getSurrogateIdentifier());
        }

        $restQuoteRequestTransfer = $restCheckoutRequestAttributesTransfer->getCart();

        if (!$restQuoteRequestTransfer || !$restQuoteRequestTransfer->getCustomer()) {
            return $restCustomerTransfer;
        }

        return $restCustomerTransfer->fromArray(
            $restQuoteRequestTransfer->getCustomer()->toArray(),
            true
        )
            ->setCustomerReference(null)
            ->setIdCustomer(null);
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
                ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
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
