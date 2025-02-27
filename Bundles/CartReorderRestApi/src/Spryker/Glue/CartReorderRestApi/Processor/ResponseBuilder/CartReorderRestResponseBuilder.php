<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartReorderRestApi\Processor\ResponseBuilder;

use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CartReorderRestApi\Dependency\Glue\CartReorderRestApiToCartsRestApiResourceInterface;
use Spryker\Glue\CartReorderRestApi\Processor\Mapper\CartReorderRestErrorMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartReorderRestResponseBuilder implements CartReorderRestResponseBuilderInterface
{
    /**
     * @param \Spryker\Glue\CartReorderRestApi\Dependency\Glue\CartReorderRestApiToCartsRestApiResourceInterface $cartsRestApiResource
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CartReorderRestApi\Processor\Mapper\CartReorderRestErrorMapperInterface $cartReorderRestErrorMapper
     */
    public function __construct(
        protected CartReorderRestApiToCartsRestApiResourceInterface $cartsRestApiResource,
        protected RestResourceBuilderInterface $restResourceBuilder,
        protected CartReorderRestErrorMapperInterface $cartReorderRestErrorMapper
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderResponseTransfer $cartReorderResponseTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildSuccessfulResponse(
        CartReorderResponseTransfer $cartReorderResponseTransfer,
        RestRequestInterface $restRequest
    ): RestResponseInterface {
        return $this->cartsRestApiResource->createCartRestResponse(
            $cartReorderResponseTransfer->getQuoteOrFail(),
            $restRequest,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderResponseTransfer $cartReorderResponseTransfer
     * @param string $locale
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildErrorResponse(CartReorderResponseTransfer $cartReorderResponseTransfer, string $locale): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        foreach ($cartReorderResponseTransfer->getErrors() as $error) {
            $restResponse->addError(
                $this->cartReorderRestErrorMapper->mapErrorTransferToRestErrorMessageTransfer(
                    $error,
                    new RestErrorMessageTransfer(),
                    $locale,
                ),
            );
        }

        return $restResponse;
    }

    /**
     * @param list<\Generated\Shared\Transfer\RestErrorMessageTransfer> $restErrorMessageTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildRequestValidationErrorResponse(array $restErrorMessageTransfers): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        foreach ($restErrorMessageTransfers as $restErrorMessageTransfer) {
            $restResponse->addError($restErrorMessageTransfer);
        }

        return $restResponse;
    }
}
