<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth\Processor\ResponseBuilder;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\MultiFactorAuth\MultiFactorAuthConfig;
use Symfony\Component\HttpFoundation\Response;

class MultiFactorAuthResponseBuilder implements MultiFactorAuthResponseBuilderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        protected RestResourceBuilderInterface $restResourceBuilder
    ) {
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createNoCustomerIdentifierErrorResponse(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $restResponse->addError(
            (new RestErrorMessageTransfer())
                ->setCode(MultiFactorAuthConfig::RESPONSE_CODE_NO_CUSTOMER_IDENTIFIER)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(MultiFactorAuthConfig::RESPONSE_DETAIL_NO_CUSTOMER_IDENTIFIER),
        );

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCustomerNotFoundResponse(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $restResponse->addError(
            (new RestErrorMessageTransfer())
                ->setCode(MultiFactorAuthConfig::RESPONSE_CUSTOMER_NOT_FOUND)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(MultiFactorAuthConfig::RESPONSE_DETAIL_CUSTOMER_NOT_FOUND),
        );

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createMissingTypeErrorResponse(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $restResponse->addError(
            (new RestErrorMessageTransfer())
                ->setCode(MultiFactorAuthConfig::ERROR_CODE_MULTI_FACTOR_AUTH_TYPE_MISSING)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(MultiFactorAuthConfig::ERROR_MESSAGE_MULTI_FACTOR_AUTH_TYPE_MISSING),
        );

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createNotFoundTypeErrorResponse(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $restResponse->addError(
            (new RestErrorMessageTransfer())
                ->setCode(MultiFactorAuthConfig::ERROR_CODE_MULTI_FACTOR_AUTH_TYPE_NOT_FOUND)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(MultiFactorAuthConfig::ERROR_MESSAGE_MULTI_FACTOR_AUTH_TYPE_NOT_FOUND),
        );

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createMissingMultiFactorAuthCodeError(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $restResponse->addError(
            (new RestErrorMessageTransfer())
                ->setCode(MultiFactorAuthConfig::ERROR_CODE_MULTI_FACTOR_AUTH_CODE_MISSING)
                ->setStatus(Response::HTTP_FORBIDDEN)
                ->setDetail(MultiFactorAuthConfig::ERROR_MESSAGE_MULTI_FACTOR_AUTH_CODE_MISSING),
        );

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createInvalidMultiFactorAuthCodeError(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $restResponse->addError(
            (new RestErrorMessageTransfer())
                ->setCode(MultiFactorAuthConfig::ERROR_CODE_MULTI_FACTOR_AUTH_CODE_INVALID)
                ->setStatus(Response::HTTP_FORBIDDEN)
                ->setDetail(MultiFactorAuthConfig::ERROR_MESSAGE_MULTI_FACTOR_AUTH_CODE_INVALID),
        );

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createDeactivationFailedError(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $restResponse->addError(
            (new RestErrorMessageTransfer())
                ->setCode(MultiFactorAuthConfig::ERROR_CODE_MULTI_FACTOR_AUTH_DEACTIVATION_FAILED)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(MultiFactorAuthConfig::ERROR_MESSAGE_MULTI_FACTOR_AUTH_DEACTIVATION_FAILED),
        );

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createAlreadyActivatedMultiFactorAuthError(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $restResponse->addError(
            (new RestErrorMessageTransfer())
                ->setCode(MultiFactorAuthConfig::ERROR_CODE_MULTI_FACTOR_AUTH_VERIFY_FAILED)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(MultiFactorAuthConfig::ERROR_MESSAGE_MULTI_FACTOR_AUTH_VERIFY_FAILED),
        );

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createSendingCodeError(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $restResponse->addError(
            (new RestErrorMessageTransfer())
                ->setCode(MultiFactorAuthConfig::RESPONSE_SENDING_CODE_ERROR)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(MultiFactorAuthConfig::ERROR_MESSAGE_SENDING_CODE_ERROR),
        );

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createSuccessResponse(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $restResponse->setStatus(Response::HTTP_NO_CONTENT);

        return $restResponse;
    }
}
