<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Shared\CartsRestApi\CartsRestApiConfig as SharedCartsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class CartRestResponseBuilder implements CartRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createRestResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $cartRestResource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartRestResponse(RestResourceInterface $cartRestResource): RestResponseInterface
    {
        return $this->createRestResponse()->addResource($cartRestResource);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartIdMissingErrorResponse(): RestResponseInterface
    {
        return $this->createRestResponse()->addError($this->createRestErrorMessageTransfer(
            SharedCartsRestApiConfig::RESPONSE_CODE_CART_ID_MISSING,
            Response::HTTP_BAD_REQUEST,
            SharedCartsRestApiConfig::EXCEPTION_MESSAGE_CART_ID_MISSING
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer[] $errors
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function returnWithErrorResponse(array $errors): RestResponseInterface
    {
        $restResponse = $this->createRestResponse();

        foreach ($errors as $messageTransfer) {
            $restErrorMessageTransfer = $this->createRestErrorMessageTransfer(
                SharedCartsRestApiConfig::RESPONSE_CODE_ITEM_VALIDATION,
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $messageTransfer->getValue()
            );
            $restResponse->addError($restErrorMessageTransfer);
        }

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createFailedDeletingCartItemErrorResponse(): RestResponseInterface
    {
        return $this->createRestResponse()->addError($this->createRestErrorMessageTransfer(
            SharedCartsRestApiConfig::RESPONSE_CODE_FAILED_DELETING_CART_ITEM,
            Response::HTTP_UNPROCESSABLE_ENTITY,
            SharedCartsRestApiConfig::EXCEPTION_MESSAGE_FAILED_DELETING_CART_ITEM
        ));
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartItemNotFoundErrorResponse(): RestResponseInterface
    {
        return $this->createRestResponse()->addError($this->createRestErrorMessageTransfer(
            SharedCartsRestApiConfig::RESPONSE_CODE_ITEM_NOT_FOUND,
            Response::HTTP_NOT_FOUND,
            SharedCartsRestApiConfig::EXCEPTION_MESSAGE_CART_ITEM_NOT_FOUND
        ));
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createMissingRequiredParameterErrorResponse(): RestResponseInterface
    {
        return $this->createRestResponse()->addError($this->createRestErrorMessageTransfer(
            SharedCartsRestApiConfig::RESPONSE_CODE_MISSING_REQUIRED_PARAMETER,
            Response::HTTP_BAD_REQUEST,
            SharedCartsRestApiConfig::EXCEPTION_MESSAGE_MISSING_REQUIRED_PARAMETER
        ));
    }

    /**
     * @param string[] $errorCodes
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildErrorRestResponseBasedOnErrorCodes(array $errorCodes): RestResponseInterface
    {
        $restResponse = $this->createRestResponse();

        foreach ($errorCodes as $errorCode) {
            $errorSignature = CartsRestApiConfig::RESPONSE_ERROR_MAP[$errorCode] ?? [
                    'status' => CartsRestApiConfig::RESPONSE_UNEXPECTED_HTTP_STATUS,
                    'detail' => $errorCode,
                ];

            $restResponse->addError(
                (new RestErrorMessageTransfer())
                    ->setCode($errorCode)
                    ->setDetail($errorSignature['detail'])
                    ->setStatus($errorSignature['status'])
            );
        }

        return $restResponse;
    }

    /**
     * @param string $code
     * @param int $status
     * @param string $detail
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createRestErrorMessageTransfer(string $code, int $status, string $detail): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode($code)
            ->setStatus($status)
            ->setDetail($detail);
    }
}
