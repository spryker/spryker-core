<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
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
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $cartRestResource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartRestResponse(RestResourceInterface $cartRestResource): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse()->addResource($cartRestResource);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createRestResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createQuoteErrorResponse(QuoteResponseTransfer $quoteResponseTransfer): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        foreach ($quoteResponseTransfer->getErrors() as $quoteErrorTransfer) {
            $restResponse->addError(
                (new RestErrorMessageTransfer())
                    ->setCode(CartsRestApiConfig::RESPONSE_CODE_CART_CANT_BE_UPDATED)
                    ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setDetail($quoteErrorTransfer->getMessage())
            );
        }

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartIdMissingErrorResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse()->addError($this->createRestErrorMessageTransfer(
            CartsRestApiConfig::RESPONSE_CODE_CART_ID_MISSING,
            Response::HTTP_BAD_REQUEST,
            CartsRestApiConfig::EXCEPTION_MESSAGE_CART_ID_MISSING
        ));
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartNotFoundErrorResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse()->addError($this->createRestErrorMessageTransfer(
            CartsRestApiConfig::RESPONSE_CODE_CART_NOT_FOUND,
            Response::HTTP_NOT_FOUND,
            CartsRestApiConfig::EXCEPTION_MESSAGE_CART_WITH_ID_NOT_FOUND
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createFailedCreatingCartErrorResponse(QuoteResponseTransfer $quoteResponseTransfer): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        if ($quoteResponseTransfer->getErrors()->count() === 0) {
            return $restResponse->addError($this->createRestErrorMessageTransfer(
                CartsRestApiConfig::RESPONSE_CODE_FAILED_CREATING_CART,
                Response::HTTP_INTERNAL_SERVER_ERROR,
                CartsRestApiConfig::EXCEPTION_MESSAGE_FAILED_TO_CREATE_CART
            ));
        }

        foreach ($quoteResponseTransfer->getErrors() as $error) {
            if ($error->getMessage() === CartsRestApiConfig::EXCEPTION_MESSAGE_CUSTOMER_ALREADY_HAS_CART) {
                $restResponse->addError($this->createRestErrorMessageTransfer(
                    CartsRestApiConfig::RESPONSE_CODE_CUSTOMER_ALREADY_HAS_CART,
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    CartsRestApiConfig::EXCEPTION_MESSAGE_CUSTOMER_ALREADY_HAS_CART
                ));

                continue;
            }

            $restResponse->addError($this->createRestErrorMessageTransfer(
                CartsRestApiConfig::RESPONSE_CODE_FAILED_CREATING_CART,
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $error->getMessage()
            ));
        }

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createFailedDeletingCartErrorResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse()->addError($this->createRestErrorMessageTransfer(
            CartsRestApiConfig::RESPONSE_CODE_FAILED_DELETING_CART,
            Response::HTTP_UNPROCESSABLE_ENTITY,
            CartsRestApiConfig::EXCEPTION_MESSAGE_FAILED_DELETING_CART
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer[] $errors
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function returnWithErrorResponse(array $errors): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($errors as $messageTransfer) {
            $restErrorMessageTransfer = $this->createRestErrorMessageTransfer(
                CartsRestApiConfig::RESPONSE_CODE_ITEM_VALIDATION,
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
        return $this->restResourceBuilder->createRestResponse()->addError($this->createRestErrorMessageTransfer(
            CartsRestApiConfig::RESPONSE_CODE_FAILED_DELETING_CART_ITEM,
            Response::HTTP_UNPROCESSABLE_ENTITY,
            CartsRestApiConfig::EXCEPTION_MESSAGE_FAILED_DELETING_CART_ITEM
        ));
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartItemNotFoundErrorResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse()->addError($this->createRestErrorMessageTransfer(
            CartsRestApiConfig::RESPONSE_CODE_ITEM_NOT_FOUND,
            Response::HTTP_NOT_FOUND,
            CartsRestApiConfig::EXCEPTION_MESSAGE_CART_ITEM_NOT_FOUND
        ));
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createMissingRequiredParameterErrorResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse()->addError($this->createRestErrorMessageTransfer(
            CartsRestApiConfig::RESPONSE_CODE_MISSING_REQUIRED_PARAMETER,
            Response::HTTP_BAD_REQUEST,
            CartsRestApiConfig::EXCEPTION_MESSAGE_MISSING_REQUIRED_PARAMETER
        ));
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
