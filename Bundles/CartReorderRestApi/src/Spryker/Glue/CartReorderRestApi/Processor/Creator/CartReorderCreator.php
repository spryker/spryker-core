<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartReorderRestApi\Processor\Creator;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\RestCartReorderRequestAttributesTransfer;
use Spryker\Glue\CartReorderRestApi\Dependency\Client\CartReorderRestApiToCartReorderClientInterface;
use Spryker\Glue\CartReorderRestApi\Processor\Mapper\CartReorderRestRequestMapperInterface;
use Spryker\Glue\CartReorderRestApi\Processor\ResponseBuilder\CartReorderRestResponseBuilderInterface;
use Spryker\Glue\CartReorderRestApi\Processor\Validator\CartReorderRestRequestValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartReorderCreator implements CartReorderCreatorInterface
{
    /**
     * @param \Spryker\Glue\CartReorderRestApi\Dependency\Client\CartReorderRestApiToCartReorderClientInterface $cartReorderClient
     * @param \Spryker\Glue\CartReorderRestApi\Processor\ResponseBuilder\CartReorderRestResponseBuilderInterface $cartReorderRestResponseBuilder
     * @param \Spryker\Glue\CartReorderRestApi\Processor\Validator\CartReorderRestRequestValidatorInterface $cartReorderRestRequestValidator
     * @param \Spryker\Glue\CartReorderRestApi\Processor\Mapper\CartReorderRestRequestMapperInterface $cartReorderRestRequestMapper
     */
    public function __construct(
        protected CartReorderRestApiToCartReorderClientInterface $cartReorderClient,
        protected CartReorderRestResponseBuilderInterface $cartReorderRestResponseBuilder,
        protected CartReorderRestRequestValidatorInterface $cartReorderRestRequestValidator,
        protected CartReorderRestRequestMapperInterface $cartReorderRestRequestMapper
    ) {
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCartReorderRequestAttributesTransfer $restCartReorderRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function reorder(
        RestRequestInterface $restRequest,
        RestCartReorderRequestAttributesTransfer $restCartReorderRequestAttributesTransfer
    ): RestResponseInterface {
        $validationGlueErrorTransfers = $this->cartReorderRestRequestValidator->validateRestRequestAttributes($restCartReorderRequestAttributesTransfer);
        if ($validationGlueErrorTransfers) {
            return $this->cartReorderRestResponseBuilder->buildRequestValidationErrorResponse($validationGlueErrorTransfers);
        }

        /** @var \Generated\Shared\Transfer\RestUserTransfer $restUserTransfer */
        $restUserTransfer = $restRequest->getRestUser();
        $cartReorderRequestTransfer = $this->cartReorderRestRequestMapper->mapRestCartReorderRequestAttributesToCartReorderRequest(
            $restCartReorderRequestAttributesTransfer,
            (new CartReorderRequestTransfer())->setCustomerReference($restUserTransfer->getNaturalIdentifierOrFail()),
        );

        $cartReorderResponseTransfer = $this->cartReorderClient->reorder($cartReorderRequestTransfer);

        return $cartReorderResponseTransfer->getErrors()->count()
            ? $this->cartReorderRestResponseBuilder->buildErrorResponse($cartReorderResponseTransfer, $restRequest->getMetadata()->getLocale())
            : $this->cartReorderRestResponseBuilder->buildSuccessfulResponse($cartReorderResponseTransfer, $restRequest);
    }
}
