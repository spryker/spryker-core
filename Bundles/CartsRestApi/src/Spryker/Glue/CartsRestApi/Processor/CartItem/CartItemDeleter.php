<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\CartItem;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RestCartItemRequestTransfer;
use Spryker\Client\CartsRestApi\CartsRestApiClientInterface;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartItemDeleter implements CartItemDeleterInterface
{
    /**
     * @var \Spryker\Client\CartsRestApi\CartsRestApiClientInterface
     */
    protected $cartsRestApiClient;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface
     */
    protected $cartRestResponseBuilder;

    /**
     * @param \Spryker\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface $cartRestResponseBuilder
     */
    public function __construct(
        CartsRestApiClientInterface $cartsRestApiClient,
        CartRestResponseBuilderInterface $cartRestResponseBuilder
    ) {
        $this->cartsRestApiClient = $cartsRestApiClient;
        $this->cartRestResponseBuilder = $cartRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function deleteItem(RestRequestInterface $restRequest): RestResponseInterface
    {
        $uuidQuote = $this->findCartIdentifier($restRequest);
        $itemIdentifier = $restRequest->getResource()->getId();
        if ($this->isRequestInvalid($uuidQuote, $itemIdentifier)) {
            return $this->cartRestResponseBuilder->createMissingRequiredParameterErrorResponse();
        }

        $restCartItemRequestTransfer = (new RestCartItemRequestTransfer())
            ->setCartUuid($uuidQuote)
            ->setCustomerReference($restRequest->getUser()->getNaturalIdentifier())
            ->setCartItem((new ItemTransfer())->setSku($itemIdentifier));

        $quoteResponseTransfer = $this->cartsRestApiClient->deleteItem($restCartItemRequestTransfer);
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->cartRestResponseBuilder->createFailedDeletingCartItemErrorResponse();
        }

        return $this->cartRestResponseBuilder->createRestResponse();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return string|null
     */
    protected function findCartIdentifier(RestRequestInterface $restRequest): ?string
    {
        $cartsResource = $restRequest->findParentResourceByType(CartsRestApiConfig::RESOURCE_CARTS);
        if ($cartsResource !== null) {
            return $cartsResource->getId();
        }

        return null;
    }

    /**
     * @param string|null $uuidQuote
     * @param string|null $itemIdentifier
     *
     * @return bool
     */
    protected function isRequestInvalid(?string $uuidQuote, ?string $itemIdentifier): bool
    {
        return ($uuidQuote === null || $itemIdentifier === null);
    }
}
