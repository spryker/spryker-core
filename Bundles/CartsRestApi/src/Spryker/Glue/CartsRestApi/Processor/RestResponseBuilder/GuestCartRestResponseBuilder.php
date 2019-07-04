<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

class GuestCartRestResponseBuilder extends AbstractCartRestResponseBuilder implements GuestCartRestResponseBuilderInterface
{
    protected const PATTERN_GUEST_CART_ITEM_RESOURCE_SELF_LINK = '%s/%s/%s/%s';
    protected const KEY_REST_RESOURCE_SELF_LINK = 'self';

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface
     */
    protected $cartsResourceMapper;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface
     */
    protected $cartItemsResourceMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface $cartsResourceMapper
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface $cartItemsResourceMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CartsResourceMapperInterface $cartsResourceMapper,
        CartItemsResourceMapperInterface $cartItemsResourceMapper
    ) {
        parent::__construct($restResourceBuilder, $cartsResourceMapper);
        $this->cartsResourceMapper = $cartsResourceMapper;
        $this->cartItemsResourceMapper = $cartItemsResourceMapper;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createEmptyGuestCartRestResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createGuestCartRestResponse(QuoteTransfer $quoteTransfer): RestResponseInterface
    {
        $cartResource = $this->restResourceBuilder->createRestResource(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS,
            $quoteTransfer->getUuid(),
            $this->cartsResourceMapper->mapQuoteTransferToRestCartsAttributesTransfer($quoteTransfer)
        );

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $cartResource->addRelationship($this->createGuestCartItemResource($itemTransfer, $cartResource->getId()));
        }

        return $this->createEmptyGuestCartRestResponse()->addResource($cartResource);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $cartResourceId
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createGuestCartItemResource(ItemTransfer $itemTransfer, string $cartResourceId): RestResourceInterface
    {
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
                $cartResourceId,
                CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                $itemTransfer->getGroupKey()
            )
        );

        return $itemResource;
    }
}
