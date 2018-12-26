<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\Checkout;

use ArrayObject;
use Generated\Shared\Transfer\RestCheckoutErrorTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Client\CheckoutRestApi\CheckoutRestApiClientInterface;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\CheckoutRestApi\Processor\RequestAttributesExpander\CheckoutRequestAttributesExpanderInterface;
use Spryker\Glue\CheckoutRestApi\Processor\Validator\CheckoutRequestValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CheckoutProcessor implements CheckoutProcessorInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Processor\RequestAttributesExpander\CheckoutRequestAttributesExpanderInterface
     */
    protected $checkoutRequestAttributesExpander;

    /**
     * @var \Spryker\Client\CheckoutRestApi\CheckoutRestApiClientInterface
     */
    protected $checkoutRestApiClient;

    /**
     * @var \Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutRequestAttributesValidatorPluginInterface[]
     */
    protected $checkoutRequestAttributesValidatorPlugins;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Processor\Validator\CheckoutRequestValidatorInterface
     */
    protected $checkoutRequestValidator;

    /**
     * @param \Spryker\Client\CheckoutRestApi\CheckoutRestApiClientInterface $checkoutRestApiClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     * @param \Spryker\Glue\CheckoutRestApi\Processor\RequestAttributesExpander\CheckoutRequestAttributesExpanderInterface $checkoutRequestAttributesExpander
     * @param \Spryker\Glue\CheckoutRestApi\Processor\Validator\CheckoutRequestValidatorInterface $checkoutRequestValidator
     */
    public function __construct(
        CheckoutRestApiClientInterface $checkoutRestApiClient,
        RestResourceBuilderInterface $restResourceBuilder,
        CheckoutRestApiToGlossaryStorageClientInterface $glossaryStorageClient,
        CheckoutRequestAttributesExpanderInterface $checkoutRequestAttributesExpander,
        CheckoutRequestValidatorInterface $checkoutRequestValidator
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->checkoutRestApiClient = $checkoutRestApiClient;
        $this->glossaryStorageClient = $glossaryStorageClient;
        $this->checkoutRequestAttributesExpander = $checkoutRequestAttributesExpander;
        $this->checkoutRequestValidator = $checkoutRequestValidator;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function placeOrder(RestRequestInterface $restRequest, RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestResponseInterface
    {
        $restErrorCollectionTransfer = $this->checkoutRequestValidator->validateCheckoutRequest($restRequest, $restCheckoutRequestAttributesTransfer);
        if ($restErrorCollectionTransfer->getRestErrors()->count()) {
            return $this->createValidationErrorResponse($restErrorCollectionTransfer);
        }

        $restCheckoutRequestAttributesTransfer = $this->checkoutRequestAttributesExpander
            ->expandCheckoutRequestAttributes($restRequest, $restCheckoutRequestAttributesTransfer);

        $restCheckoutResponseTransfer = $this->checkoutRestApiClient->placeOrder($restCheckoutRequestAttributesTransfer);
        if (!$restCheckoutResponseTransfer->getIsSuccess()) {
            return $this->createPlaceOrderFailedErrorResponse($restCheckoutResponseTransfer->getErrors(), $restRequest->getMetadata()->getLocale());
        }

        return $this->createOrderPlacedResponse($restCheckoutResponseTransfer->getOrderReference());
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutErrorTransfer[]|\ArrayObject $errors
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createPlaceOrderFailedErrorResponse(ArrayObject $errors, string $localeName): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($errors as $restCheckoutErrorTransfer) {
            $restResponse->addError((new RestErrorMessageTransfer())
                ->setCode($restCheckoutErrorTransfer->getCode())
                ->setStatus($restCheckoutErrorTransfer->getStatus())
                ->setDetail($this->translateCheckoutErrorMessage($restCheckoutErrorTransfer, $localeName)));
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutErrorTransfer $restCheckoutErrorTransfer
     * @param string $localeName
     *
     * @return string
     */
    protected function translateCheckoutErrorMessage(RestCheckoutErrorTransfer $restCheckoutErrorTransfer, string $localeName): string
    {
        $checkoutErrorMessage = $restCheckoutErrorTransfer->getDetail();

        return $this->glossaryStorageClient->translate(
            $checkoutErrorMessage,
            $localeName,
            $restCheckoutErrorTransfer->getParameters()
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

        return $this->restResourceBuilder
            ->createRestResponse()
            ->addResource($restResource);
    }

    /**
     * @param \Generated\Shared\Transfer\RestErrorCollectionTransfer $restErrorCollectionTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createValidationErrorResponse(RestErrorCollectionTransfer $restErrorCollectionTransfer)
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        foreach ($restErrorCollectionTransfer->getRestErrors() as $restErrorMessageTransfer) {
            $restResponse->addError($restErrorMessageTransfer);
        }

        return $restResponse;
    }
}
