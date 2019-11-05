<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi\Processor\CartCodeDeleter;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\CartCodesRestApi\CartCodesRestApiClientInterface;
use Spryker\Glue\CartCodesRestApi\Processor\RestResponseBuilder\CartCodeRestResponseBuilderInterface;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartCodeDeleter implements CartCodeDeleterInterface
{
    /**
     * @var \Spryker\Client\CartCodesRestApi\CartCodesRestApiClientInterface
     */
    protected $cartCodesRestApiClient;

    /**
     * @var \Spryker\Glue\CartCodesRestApi\Processor\RestResponseBuilder\CartCodeRestResponseBuilderInterface
     */
    protected $cartCodeResponseBuilder;

    /**
     * @param \Spryker\Client\CartCodesRestApi\CartCodesRestApiClientInterface $cartCodesClient
     * @param \Spryker\Glue\CartCodesRestApi\Processor\RestResponseBuilder\CartCodeRestResponseBuilderInterface $cartCodeResponseBuilder
     */
    public function __construct(
        CartCodesRestApiClientInterface $cartCodesClient,
        CartCodeRestResponseBuilderInterface $cartCodeResponseBuilder
    ) {
        $this->cartCodesRestApiClient = $cartCodesClient;
        $this->cartCodeResponseBuilder = $cartCodeResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function removeCodeFromCart(RestRequestInterface $restRequest): RestResponseInterface
    {
        $quoteTransfer = $this->createQuoteTransfer($restRequest, CartsRestApiConfig::RESOURCE_CARTS);

        return $this->removeCode($quoteTransfer, $restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function removeCodeFromGuestCart(RestRequestInterface $restRequest): RestResponseInterface
    {
        $quoteTransfer = $this->createQuoteTransfer($restRequest, CartsRestApiConfig::RESOURCE_GUEST_CARTS);

        return $this->removeCode($quoteTransfer, $restRequest);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function removeCode(QuoteTransfer $quoteTransfer, RestRequestInterface $restRequest): RestResponseInterface
    {
        $cartCodeOperationResultTransfer = $this->cartCodesRestApiClient->removeCode(
            $quoteTransfer,
            $restRequest->getResource()->getId()
        );

        return $this->cartCodeResponseBuilder->buildCartRestResponse($cartCodeOperationResultTransfer, $restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string $resourceType
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(RestRequestInterface $restRequest, string $resourceType): QuoteTransfer
    {
        $cartResource = $restRequest->findParentResourceByType($resourceType);
        $customerReference = $restRequest->getRestUser()->getNaturalIdentifier();
        $customerTransfer = (new CustomerTransfer())->setCustomerReference($customerReference);

        return (new QuoteTransfer())
            ->setUuid($cartResource->getId())
            ->setCustomer($customerTransfer)
            ->setCustomerReference($customerReference);
    }
}
