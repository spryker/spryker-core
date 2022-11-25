<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Expander;

use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartByRestCheckoutDataResourceRelationshipExpander implements CartByQuoteResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface
     */
    protected CartRestResponseBuilderInterface $cartRestResponseBuilder;

    /**
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface $cartRestResponseBuilder
     */
    public function __construct(
        CartRestResponseBuilderInterface $cartRestResponseBuilder
    ) {
        $this->cartRestResponseBuilder = $cartRestResponseBuilder;
    }

    /**
     * @param array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $customerReference = $restRequest->getRestUser() ? $restRequest->getRestUser()->getNaturalIdentifier() : null;

        if (!$customerReference || !$this->isAuthorisedUser($restRequest)) {
            return;
        }

        foreach ($resources as $resource) {
            $restCheckoutDataTransfer = $resource->getPayload();
            if (
                !$restCheckoutDataTransfer instanceof RestCheckoutDataTransfer
                || $restCheckoutDataTransfer->getQuote() === null
            ) {
                continue;
            }
            $this->addCartResourceRelationships($resource, $restRequest->getMetadata()->getLocale());
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param string $locale
     *
     * @return void
     */
    protected function addCartResourceRelationships(RestResourceInterface $resource, string $locale): void
    {
        /** @var \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer */
        $restCheckoutDataTransfer = $resource->getPayload();

        $quoteTransfer = $restCheckoutDataTransfer->getQuote();
        $cartRestResponse = $this->cartRestResponseBuilder->createCartRestResponse($quoteTransfer, $locale);

        foreach ($cartRestResponse->getResources() as $cartRestResource) {
            $resource->addRelationship($cartRestResource);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    protected function isAuthorisedUser(RestRequestInterface $restRequest): bool
    {
        return $restRequest->getHttpRequest()->headers->has(CartsRestApiConfig::HEADER_AUTHORIZATION);
    }
}
