<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\CartItem;

use Generated\Shared\Transfer\RestCartItemRequestTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Client\CartsRestApi\CartsRestApiClientInterface;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToZedRequestClientInterface;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartItemAdder implements CartItemAdderInterface
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
     * @var \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface
     */
    protected $cartItemsResourceMapper;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface
     */
    protected $cartsResourceMapper;

    /**
     * @param \Spryker\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface $cartRestResponseBuilder
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToZedRequestClientInterface $zedRequestClient
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface $cartItemsResourceMapper
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface $cartsResourceMapper
     */
    public function __construct(
        CartsRestApiClientInterface $cartsRestApiClient,
        CartRestResponseBuilderInterface $cartRestResponseBuilder,
        CartsRestApiToZedRequestClientInterface $zedRequestClient,
        CartItemsResourceMapperInterface $cartItemsResourceMapper,
        CartsResourceMapperInterface $cartsResourceMapper
    ) {
        $this->cartsRestApiClient = $cartsRestApiClient;
        $this->cartRestResponseBuilder = $cartRestResponseBuilder;
        $this->zedRequestClient = $zedRequestClient;
        $this->cartItemsResourceMapper = $cartItemsResourceMapper;
        $this->cartsResourceMapper = $cartsResourceMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addItem(
        RestRequestInterface $restRequest,
        RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
    ): RestResponseInterface {
        $uuidQuote = $this->findCartIdentifier($restRequest);
        if ($uuidQuote === null) {
            return $this->cartRestResponseBuilder->createCartIdMissingErrorResponse();
        }

        $restCartItemRequestTransfer = (new RestCartItemRequestTransfer())
            ->setCartItem($this->cartItemsResourceMapper->mapItemAttributesToItemTransfer($restCartItemsAttributesTransfer))
            ->setCartUuid($uuidQuote)
            ->setCustomerReference($restRequest->getUser()->getNaturalIdentifier());

        $quoteTransfer = $this->cartsRestApiClient->addItem($restCartItemRequestTransfer)->getQuoteTransfer();

        $errors = $this->zedRequestClient->getLastResponseErrorMessages();
        if (count($errors) > 0) {
            return $this->cartRestResponseBuilder->returnWithErrorResponse($errors);
        }

        $restResource = $this->cartsResourceMapper->mapCartsResource(
            $quoteTransfer,
            $restRequest
        );

        return $this->cartRestResponseBuilder->createCartRestResponse($restResource);
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
}
