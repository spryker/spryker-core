<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\GuestCartItem;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Client\CartsRestApi\CartsRestApiClientInterface;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface;
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
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface
     */
    protected $cartItemsResourceMapper;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface
     */
    protected $guestCartRestResponseBuilder;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface
     */
    protected $cartRestResponseBuilder;

    /**
     * @param \Spryker\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface $cartItemsResourceMapper
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface $cartRestResponseBuilder
     */
    public function __construct(
        CartsRestApiClientInterface $cartsRestApiClient,
        CartItemsResourceMapperInterface $cartItemsResourceMapper,
        GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder,
        CartRestResponseBuilderInterface $cartRestResponseBuilder
    ) {
        $this->cartsRestApiClient = $cartsRestApiClient;
        $this->cartItemsResourceMapper = $cartItemsResourceMapper;
        $this->guestCartRestResponseBuilder = $guestCartRestResponseBuilder;
        $this->cartRestResponseBuilder = $cartRestResponseBuilder;
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
        $cartItemRequestTransfer = $this->createCartItemRequestTransfer($restRequest, $restCartItemsAttributesTransfer);
        $quoteResponseTransfer = $this->cartsRestApiClient->addToGuestCart($cartItemRequestTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->cartRestResponseBuilder->createFailedErrorResponse($quoteResponseTransfer->getErrors());
        }

        return $this->guestCartRestResponseBuilder->createGuestCartRestResponse($quoteResponseTransfer->getQuoteTransfer());
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return string|null
     */
    protected function findGuestCartIdentifier(RestRequestInterface $restRequest): ?string
    {
        $cartsResource = $restRequest->findParentResourceByType(CartsRestApiConfig::RESOURCE_GUEST_CARTS);
        if ($cartsResource) {
            return $cartsResource->getId();
        }

        return null;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemRequestTransfer
     */
    protected function createCartItemRequestTransfer(
        RestRequestInterface $restRequest,
        RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
    ): CartItemRequestTransfer {
        return (new CartItemRequestTransfer())
            ->setQuoteUuid($this->findGuestCartIdentifier($restRequest))
            ->setSku($restCartItemsAttributesTransfer->getSku())
            ->setQuantity($restCartItemsAttributesTransfer->getQuantity())
            ->setCustomer((new CustomerTransfer())->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier()));
    }
}
