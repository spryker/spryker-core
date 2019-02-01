<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\GuestCartItem;

use Generated\Shared\Transfer\RestCartItemRequestTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Client\CartsRestApi\CartsRestApiClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToZedRequestClientInterface;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class GuestCartItemAdder implements GuestCartItemAdderInterface
{
    /**
     * @var \Spryker\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     */
    protected $cartsRestApiClient;

    /**
     * @var \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface
     */
    protected $cartItemsResourceMapper;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface
     */
    protected $guestCartRestResponseBuilder;

    /**
     * @param \Spryker\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToZedRequestClientInterface $zedRequestClient
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface $cartItemsResourceMapper
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder
     */
    public function __construct(
        CartsRestApiClientInterface $cartsRestApiClient,
        CartsRestApiToZedRequestClientInterface $zedRequestClient,
        CartItemsResourceMapperInterface $cartItemsResourceMapper,
        GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder
    ) {
        $this->cartsRestApiClient = $cartsRestApiClient;
        $this->zedRequestClient = $zedRequestClient;
        $this->cartItemsResourceMapper = $cartItemsResourceMapper;
        $this->guestCartRestResponseBuilder = $guestCartRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addItemToGuestCart(
        RestRequestInterface $restRequest,
        RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
    ): RestResponseInterface {
        $restCartItemRequestTransfer = (new RestCartItemRequestTransfer())
            ->setCartItem($this->cartItemsResourceMapper->mapItemAttributesToItemTransfer($restCartItemsAttributesTransfer))
            ->setCartUuid($restRequest->getResource()->getId())
            ->setCustomerReference($restRequest->getUser()->getNaturalIdentifier());

        $quoteResponseTransfer = $this->cartsRestApiClient->addItemToGuestCart($restCartItemRequestTransfer);

        $errors = $this->zedRequestClient->getLastResponseErrorMessages();
        if (count($errors) > 0) {
            return $this->guestCartRestResponseBuilder->createGuestCartErrorRestResponseFromErrorMessageTransfer($errors);
        }

        return $this->guestCartRestResponseBuilder->createGuestCartRestResponse($quoteResponseTransfer->getQuoteTransfer());
    }
}
