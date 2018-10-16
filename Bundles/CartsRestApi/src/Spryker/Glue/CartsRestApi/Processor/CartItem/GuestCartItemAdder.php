<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\CartItem;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface;
use Spryker\Glue\CartsRestApi\Processor\Cart\GuestCartCreatorInterface;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class GuestCartItemAdder implements GuestCartItemAdderInterface
{
    protected const PATTERN_GUEST_CART_ITEM_RESOURCE_SELF_LINK = '%s/%s/%s/%s';
    protected const KEY_REST_RESOURCE_SELF_LINK = 'self';

    /**
     * @var \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface
     */
    protected $cartReader;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Cart\GuestCartCreatorInterface
     */
    protected $guestCartCreator;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface
     */
    protected $cartsResourceMapper;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface
     */
    protected $cartItemsResourceMapper;

    /**
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface $cartClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface $quoteClient
     * @param \Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface $cartReader
     * @param \Spryker\Glue\CartsRestApi\Processor\Cart\GuestCartCreatorInterface $guestCartCreator
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface $cartsResourceMapper
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface $cartItemsResourceMapper
     */
    public function __construct(
        CartsRestApiToCartClientInterface $cartClient,
        RestResourceBuilderInterface $restResourceBuilder,
        CartsRestApiToQuoteClientInterface $quoteClient,
        CartReaderInterface $cartReader,
        GuestCartCreatorInterface $guestCartCreator,
        CartsResourceMapperInterface $cartsResourceMapper,
        CartItemsResourceMapperInterface $cartItemsResourceMapper
    ) {
        $this->cartClient = $cartClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->quoteClient = $quoteClient;
        $this->cartReader = $cartReader;
        $this->guestCartCreator = $guestCartCreator;
        $this->cartsResourceMapper = $cartsResourceMapper;
        $this->cartItemsResourceMapper = $cartItemsResourceMapper;
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
        $quoteResponseTransfers = $this->cartReader->getCustomerQuotes();
        $quotes = $quoteResponseTransfers->getQuotes();
        $quoteTransfer = $quotes[0] ?? $this->guestCartCreator->create($restRequest);
        $this->quoteClient->setQuote($quoteTransfer);
        $quoteTransfer = $this->cartClient->addItem(
            $this->cartItemsResourceMapper->mapItemAttributesToItemTransfer($restCartItemsAttributesTransfer)
        );

        return $this->getGuestCartRestResponse($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getGuestCartRestResponse(QuoteTransfer $quoteTransfer): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $restCartsAttributesTransfer = $this->cartsResourceMapper->mapQuoteTransferToRestCartsAttributesTransfer($quoteTransfer);
        $cartResource = $this->restResourceBuilder->createRestResource(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS,
            $quoteTransfer->getUuid(),
            $restCartsAttributesTransfer
        );

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemResource = $this->restResourceBuilder->createRestResource(
                CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                $itemTransfer->getGroupKey(),
                $this->cartItemsResourceMapper->mapCartItemAttributes($itemTransfer)
            );
            $itemResource->addLink(
                static::KEY_REST_RESOURCE_SELF_LINK,
                sprintf(
                    static::PATTERN_GUEST_CART_ITEM_RESOURCE_SELF_LINK,
                    CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                    $cartResource->getId(),
                    CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    $itemTransfer->getGroupKey()
                )
            );

            $cartResource->addRelationship($itemResource);
        }

        return $restResponse->addResource($cartResource);
    }
}
