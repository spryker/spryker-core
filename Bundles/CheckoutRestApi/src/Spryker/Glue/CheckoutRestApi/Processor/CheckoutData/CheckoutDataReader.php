<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\CheckoutData;

use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutDataResponseTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Client\CheckoutRestApi\CheckoutRestApiClientInterface;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Glue\CheckoutRestApi\Processor\Error\RestCheckoutErrorMapperInterface;
use Spryker\Glue\CheckoutRestApi\Processor\RequestAttributesExpander\CheckoutRequestAttributesExpanderInterface;
use Spryker\Glue\CheckoutRestApi\Processor\Validator\CheckoutRequestValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class CheckoutDataReader implements CheckoutDataReaderInterface
{
    /**
     * @var \Spryker\Client\CheckoutRestApi\CheckoutRestApiClientInterface
     */
    protected $checkoutRestApiClient;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface
     */
    protected $checkoutDataMapper;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Processor\RequestAttributesExpander\CheckoutRequestAttributesExpanderInterface
     */
    protected $checkoutRequestAttributesExpander;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Processor\Validator\CheckoutRequestValidatorInterface
     */
    protected $checkoutRequestValidator;

    /**
     * @var \Spryker\Glue\CheckoutRestApi\Processor\Error\RestCheckoutErrorMapperInterface
     */
    protected $restCheckoutErrorMapper;

    /**
     * @param \Spryker\Client\CheckoutRestApi\CheckoutRestApiClientInterface $checkoutRestApiClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface $checkoutDataMapper
     * @param \Spryker\Glue\CheckoutRestApi\Processor\RequestAttributesExpander\CheckoutRequestAttributesExpanderInterface $checkoutRequestAttributesExpander
     * @param \Spryker\Glue\CheckoutRestApi\Processor\Validator\CheckoutRequestValidatorInterface $checkoutRequestValidator
     * @param \Spryker\Glue\CheckoutRestApi\Processor\Error\RestCheckoutErrorMapperInterface $restCheckoutErrorMapper
     */
    public function __construct(
        CheckoutRestApiClientInterface $checkoutRestApiClient,
        RestResourceBuilderInterface $restResourceBuilder,
        CheckoutDataMapperInterface $checkoutDataMapper,
        CheckoutRequestAttributesExpanderInterface $checkoutRequestAttributesExpander,
        CheckoutRequestValidatorInterface $checkoutRequestValidator,
        RestCheckoutErrorMapperInterface $restCheckoutErrorMapper
    ) {
        $this->checkoutRestApiClient = $checkoutRestApiClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->checkoutDataMapper = $checkoutDataMapper;
        $this->checkoutRequestAttributesExpander = $checkoutRequestAttributesExpander;
        $this->checkoutRequestValidator = $checkoutRequestValidator;
        $this->restCheckoutErrorMapper = $restCheckoutErrorMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCheckoutData(
        RestRequestInterface $restRequest,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestResponseInterface {
        $restErrorCollectionTransfer = $this->checkoutRequestValidator->validateCheckoutRequest(
            $restRequest,
            $restCheckoutRequestAttributesTransfer
        );

        if ($restErrorCollectionTransfer->getRestErrors()->count()) {
            return $this->createValidationErrorResponse($restErrorCollectionTransfer);
        }

        $restCheckoutRequestAttributesTransfer = $this->checkoutRequestAttributesExpander
            ->expandCheckoutRequestAttributes($restRequest, $restCheckoutRequestAttributesTransfer);

        $restCheckoutDataResponseTransfer = $this
            ->checkoutRestApiClient
            ->getCheckoutData($restCheckoutRequestAttributesTransfer);

        if (!$restCheckoutDataResponseTransfer->getIsSuccess()) {
            return $this->createCheckoutDataErrorResponse($restCheckoutDataResponseTransfer);
        }

        $restCheckoutResponseAttributesTransfer = $this->checkoutDataMapper
            ->mapRestCheckoutDataTransferToRestCheckoutDataResponseAttributesTransfer(
                $restCheckoutDataResponseTransfer->getCheckoutData(),
                $restCheckoutRequestAttributesTransfer
            );

        return $this->createRestResponse($restCheckoutResponseAttributesTransfer, $restCheckoutDataResponseTransfer->getCheckoutData());
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutResponseAttributesTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createRestResponse(
        RestCheckoutDataResponseAttributesTransfer $restCheckoutResponseAttributesTransfer,
        RestCheckoutDataTransfer $restCheckoutDataTransfer
    ): RestResponseInterface {
        $checkoutDataResource = $this->createRestResource(
            $restCheckoutResponseAttributesTransfer,
            $restCheckoutDataTransfer
        );

        $restResponse = $this->restResourceBuilder
            ->createRestResponse()
            ->addResource($checkoutDataResource)
            ->setStatus(Response::HTTP_OK);

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutResponseAttributesTransfer
     * @param RestCheckoutDataTransfer $restCheckoutDataTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createRestResource(
        RestCheckoutDataResponseAttributesTransfer $restCheckoutResponseAttributesTransfer,
        RestCheckoutDataTransfer $restCheckoutDataTransfer
    ): RestResourceInterface {
        $checkoutDataResource = $this->restResourceBuilder->createRestResource(
            CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
            null,
            $restCheckoutResponseAttributesTransfer
        );

        if ($restCheckoutDataTransfer) {
            $checkoutDataResource->setPayload($restCheckoutDataTransfer);
        }

        return $checkoutDataResource;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseTransfer $restCheckoutDataResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createCheckoutDataErrorResponse(RestCheckoutDataResponseTransfer $restCheckoutDataResponseTransfer): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        foreach ($restCheckoutDataResponseTransfer->getErrors() as $restCheckoutErrorTransfer) {
            $restResponse->addError(
                $this->restCheckoutErrorMapper->mapRestCheckoutErrorTransferToRestErrorTransfer(
                    $restCheckoutErrorTransfer,
                    new RestErrorMessageTransfer()
                )
            );
        }

        return $restResponse;
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
