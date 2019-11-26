<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi\Processor\RestResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
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
     * @param \Generated\Shared\Transfer\CartCodeOperationResultTransfer $cartCodeOperationResultTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildCartRestResponse(
        CartCodeOperationResultTransfer $cartCodeOperationResultTransfer,
        RestRequestInterface $restRequest
    ): RestResponseInterface {
        $quoteTransfer = $cartCodeOperationResultTransfer->getQuote();
        if (!$quoteTransfer) {
            return $this->createFailedErrorResponse($cartCodeOperationResultTransfer->getMessages());
        }

        return $this->cartsRestApiResource->createCartRestResponse($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartCodeOperationResultTransfer $cartCodeOperationResultTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildGuestCartRestResponse(CartCodeOperationResultTransfer $cartCodeOperationResultTransfer): RestResponseInterface
    {
        $quoteTransfer = $cartCodeOperationResultTransfer->getQuote();
        if (!$quoteTransfer) {
            return $this->createFailedErrorResponse($cartCodeOperationResultTransfer->getMessages());
        }

        return $this->cartsRestApiResource->createGuestCartRestResponse($quoteTransfer);
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
