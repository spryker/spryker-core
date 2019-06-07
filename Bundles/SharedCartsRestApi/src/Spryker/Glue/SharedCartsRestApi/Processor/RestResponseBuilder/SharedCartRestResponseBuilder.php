<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestSharedCartsAttributesTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Mapper\SharedCartMapperInterface;
use Spryker\Glue\SharedCartsRestApi\SharedCartsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class SharedCartRestResponseBuilder implements SharedCartRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Mapper\SharedCartMapperInterface
     */
    protected $sharedCartMapper;

    /**
     * @var \Spryker\Glue\SharedCartsRestApi\SharedCartsRestApiConfig
     */
    protected $sharedCartsRestApiConfig;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Mapper\SharedCartMapperInterface $sharedCartMapper
     * @param \Spryker\Glue\SharedCartsRestApi\SharedCartsRestApiConfig $sharedCartsRestApiConfig
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        SharedCartMapperInterface $sharedCartMapper,
        SharedCartsRestApiConfig $sharedCartsRestApiConfig
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->sharedCartMapper = $sharedCartMapper;
        $this->sharedCartsRestApiConfig = $sharedCartsRestApiConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ShareDetailTransfer|null $shareDetailTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createSharedCartRestResponse(?ShareDetailTransfer $shareDetailTransfer = null): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        if (!$shareDetailTransfer) {
            return $restResponse;
        }

        $restSharedCartsAttributesTransfer = $this->sharedCartMapper->mapShareDetailTransferToRestSharedCartsAttributesTransfer(
            $shareDetailTransfer,
            new RestSharedCartsAttributesTransfer()
        );

        return $restResponse->addResource(
            $this->createRestSharedCartsResource(
                $shareDetailTransfer->getUuid(),
                $restSharedCartsAttributesTransfer
            )
        );
    }

    /**
     * @param string $errorIdentifier
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createErrorResponseFromErrorIdentifier(string $errorIdentifier): RestResponseInterface
    {
        $errorMappingData = $this->sharedCartsRestApiConfig->getErrorIdentifierToRestErrorMapping();
        if (!isset($errorMappingData[$errorIdentifier])) {
            return $this->restResourceBuilder->createRestResponse()
                ->addError($this->createDefaultUnexpectedRestErrorMessage($errorIdentifier));
        }

        return $this->restResourceBuilder->createRestResponse()
            ->addError($this->createRestErrorMessageFromErrorData($errorMappingData[$errorIdentifier]));
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartIdMissingErrorResponse(): RestResponseInterface
    {
        return $this->createRestErrorResponse(
            Response::HTTP_BAD_REQUEST,
            SharedCartsRestApiConfig::RESPONSE_CODE_CART_ID_MISSING,
            SharedCartsRestApiConfig::EXCEPTION_MESSAGE_CART_ID_MISSING
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyUserNotFoundErrorResponse(): RestResponseInterface
    {
        return $this->createRestErrorResponse(
            Response::HTTP_NOT_FOUND,
            SharedCartsRestApiConfig::RESPONSE_CODE_COMPANY_USER_NOT_FOUND,
            SharedCartsRestApiConfig::RESPONSE_DETAIL_COMPANY_USER_NOT_FOUND
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createSharingForbiddenErrorResponse(): RestResponseInterface
    {
        return $this->createRestErrorResponse(
            Response::HTTP_FORBIDDEN,
            SharedCartsRestApiConfig::RESPONSE_CODE_SHARE_CART_OUTSIDE_THE_COMPANY_FORBIDDEN,
            SharedCartsRestApiConfig::RESPONSE_DETAIL_SHARE_CART_OUTSIDE_THE_COMPANY_FORBIDDEN
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createSharedCartIdMissingErrorResponse(): RestResponseInterface
    {
        return $this->createRestErrorResponse(
            Response::HTTP_BAD_REQUEST,
            SharedCartsRestApiConfig::RESPONSE_CODE_SHARED_CART_ID_MISSING,
            SharedCartsRestApiConfig::RESPONSE_DETAIL_SHARED_CART_ID_MISSING
        );
    }

    /**
     * @param int $status
     * @param string $code
     * @param string $detail
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createRestErrorResponse(int $status, string $code, string $detail): RestResponseInterface
    {
        $restErrorMessageTransfer = $this->createRestErrorMessageFromErrorData([
            RestErrorMessageTransfer::STATUS => $status,
            RestErrorMessageTransfer::CODE => $code,
            RestErrorMessageTransfer::DETAIL => $detail,
        ]);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @param string $sharedCartUuid
     * @param \Generated\Shared\Transfer\RestSharedCartsAttributesTransfer $restSharedCartsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createRestSharedCartsResource(string $sharedCartUuid, RestSharedCartsAttributesTransfer $restSharedCartsAttributesTransfer): RestResourceInterface
    {
        return $this->restResourceBuilder->createRestResource(
            SharedCartsRestApiConfig::RESOURCE_SHARED_CARTS,
            $sharedCartUuid,
            $restSharedCartsAttributesTransfer
        );
    }

    /**
     * @param string $errorIdentifier
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createDefaultUnexpectedRestErrorMessage(string $errorIdentifier): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->setDetail($errorIdentifier);
    }

    /**
     * @param array $errorData
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createRestErrorMessageFromErrorData(array $errorData): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())->fromArray($errorData);
    }
}
