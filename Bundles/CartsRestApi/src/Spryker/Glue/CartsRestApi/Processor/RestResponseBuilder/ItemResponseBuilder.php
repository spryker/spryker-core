<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class ItemResponseBuilder implements ItemResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemMapperInterface
     */
    protected $cartItemMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemMapperInterface $cartItemMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CartItemMapperInterface $cartItemMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->cartItemMapper = $cartItemMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $cartResource
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createCartItemResource(
        RestResourceInterface $cartResource,
        ItemTransfer $itemTransfer,
        string $localeName
    ): RestResourceInterface {
        $itemResource = $this->restResourceBuilder->createRestResource(
            CartsRestApiConfig::RESOURCE_CART_ITEMS,
            $itemTransfer->getGroupKey(),
            $this->cartItemMapper->mapItemTransferToRestItemsAttributesTransfer(
                $itemTransfer,
                $localeName
            )
        );

        return $this->addSelfLinkToCartItemResource($itemResource, $cartResource, $itemTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $guestCartResource
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createGuestCartItemResource(
        RestResourceInterface $guestCartResource,
        ItemTransfer $itemTransfer,
        string $localeName
    ): RestResourceInterface {
        $itemResource = $this->restResourceBuilder->createRestResource(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            $itemTransfer->getGroupKey(),
            $this->cartItemMapper->mapItemTransferToRestItemsAttributesTransfer(
                $itemTransfer,
                $localeName
            )
        );

        return $this->addSelfLinkToCartGuestItemResource($itemResource, $guestCartResource, $itemTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $itemResource
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $cartResource
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function addSelfLinkToCartItemResource(
        RestResourceInterface $itemResource,
        RestResourceInterface $cartResource,
        ItemTransfer $itemTransfer
    ): RestResourceInterface {
        return $itemResource->addLink(
            RestLinkInterface::LINK_SELF,
            sprintf(
                '%s/%s/%s/%s',
                CartsRestApiConfig::RESOURCE_CARTS,
                $cartResource->getId(),
                CartsRestApiConfig::RESOURCE_CART_ITEMS,
                $itemTransfer->getGroupKey()
            )
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $itemResource
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $guestCartResource
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function addSelfLinkToCartGuestItemResource(
        RestResourceInterface $itemResource,
        RestResourceInterface $guestCartResource,
        ItemTransfer $itemTransfer
    ): RestResourceInterface {
        return $itemResource->addLink(
            RestLinkInterface::LINK_SELF,
            sprintf(
                '%s/%s/%s/%s',
                CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                $guestCartResource->getId(),
                CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                $itemTransfer->getGroupKey()
            )
        );
    }
}
