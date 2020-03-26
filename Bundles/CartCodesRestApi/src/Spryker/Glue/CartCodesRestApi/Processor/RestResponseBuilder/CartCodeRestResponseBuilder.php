<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi\Processor\RestResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\CartCodeResponseTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CartCodesRestApi\Dependency\RestApiResource\CartCodesRestApiToCartsRestApiResourceInterface;
use Spryker\Glue\CartCodesRestApi\Processor\Mapper\CartCodeMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartCodeRestResponseBuilder implements CartCodeRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CartCodesRestApi\Processor\Mapper\CartCodeMapperInterface
     */
    protected $cartCodeMapper;

    /**
     * @var \Spryker\Glue\CartCodesRestApi\Dependency\RestApiResource\CartCodesRestApiToCartsRestApiResourceInterface
     */
    protected $cartsRestApiResource;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CartCodesRestApi\Processor\Mapper\CartCodeMapperInterface $cartCodeMapper
     * @param \Spryker\Glue\CartCodesRestApi\Dependency\RestApiResource\CartCodesRestApiToCartsRestApiResourceInterface $cartsRestApiResource
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CartCodeMapperInterface $cartCodeMapper,
        CartCodesRestApiToCartsRestApiResourceInterface $cartsRestApiResource
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->cartCodeMapper = $cartCodeMapper;
        $this->cartsRestApiResource = $cartsRestApiResource;
    }

    /**
     * @param \Generated\Shared\Transfer\CartCodeResponseTransfer $cartCodeResponseTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartRestResponse(
        CartCodeResponseTransfer $cartCodeResponseTransfer,
        RestRequestInterface $restRequest
    ): RestResponseInterface {
        $quoteTransfer = $cartCodeResponseTransfer->getQuote();
        if (!$quoteTransfer) {
            return $this->createFailedErrorResponse($cartCodeResponseTransfer->getMessages());
        }

        return $this->cartsRestApiResource->createCartRestResponse($quoteTransfer, $restRequest);
    }

    /**
     * @param \Generated\Shared\Transfer\CartCodeResponseTransfer $cartCodeResponseTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createGuestCartRestResponse(
        CartCodeResponseTransfer $cartCodeResponseTransfer,
        RestRequestInterface $restRequest
    ): RestResponseInterface {
        $quoteTransfer = $cartCodeResponseTransfer->getQuote();
        if (!$quoteTransfer) {
            return $this->createFailedErrorResponse($cartCodeResponseTransfer->getMessages());
        }

        return $this->cartsRestApiResource->createGuestCartRestResponse($quoteTransfer, $restRequest);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[] $messageTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createFailedErrorResponse(ArrayObject $messageTransfers): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($messageTransfers as $messageTransfer) {
            $restResponse->addError(
                $this->cartCodeMapper->mapMessageTransferToRestErrorMessageTransfer(
                    $messageTransfer,
                    new RestErrorMessageTransfer()
                )
            );
        }

        return $restResponse;
    }
}
