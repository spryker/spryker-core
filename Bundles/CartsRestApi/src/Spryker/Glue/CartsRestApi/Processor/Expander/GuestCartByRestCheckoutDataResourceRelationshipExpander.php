<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Expander;

use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class GuestCartByRestCheckoutDataResourceRelationshipExpander implements GuestCartByQuoteResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface
     */
    protected GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder;

    /**
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder
     */
    public function __construct(
        GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder
    ) {
        $this->guestCartRestResponseBuilder = $guestCartRestResponseBuilder;
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
        if (!$customerReference || !$this->isAnonymousUser($restRequest)) {
            return;
        }

        foreach ($resources as $resource) {
            $restCheckoutDataTransfer = $resource->getPayload();
            if (
                !$restCheckoutDataTransfer instanceof RestCheckoutDataTransfer ||
                !$restCheckoutDataTransfer->getQuote()
            ) {
                continue;
            }
            $guestCartRestResponse = $this->guestCartRestResponseBuilder->createGuestCartRestResponse(
                $restCheckoutDataTransfer->getQuote(),
                $restRequest->getMetadata()->getLocale(),
            );

            foreach ($guestCartRestResponse->getResources() as $cartRestResource) {
                $resource->addRelationship($cartRestResource);
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    protected function isAnonymousUser(RestRequestInterface $restRequest): bool
    {
        return $restRequest->getHttpRequest()->headers->has(CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID);
    }
}
