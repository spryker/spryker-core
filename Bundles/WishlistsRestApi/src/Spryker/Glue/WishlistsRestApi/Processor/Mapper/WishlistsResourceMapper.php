<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestWishlistsAttributesTransfer;
use Generated\Shared\Transfer\WishlistOverviewResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig;

class WishlistsResourceMapper implements WishlistsResourceMapperInterface
{
    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemsResourceMapperInterface
     */
    protected $wishlistItemsResourceMapper;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemsResourceMapperInterface $wishlistItemsResourceMapper
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        WishlistItemsResourceMapperInterface $wishlistItemsResourceMapper,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->wishlistItemsResourceMapper = $wishlistItemsResourceMapper;
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapWishlistTransferToRestResource(WishlistTransfer $wishlistTransfer): RestResourceInterface
    {
        $restWishlistsAttributesTransfer = (new RestWishlistsAttributesTransfer())->fromArray($wishlistTransfer->toArray(), true);

        $wishlistsResource = $this->restResourceBuilder->createRestResource(
            WishlistsRestApiConfig::RESOURCE_WISHLISTS,
            $wishlistTransfer->getUuid(),
            $restWishlistsAttributesTransfer
        );

        return $wishlistsResource;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistOverviewResponseTransfer $wishlistOverviewResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapWishlistOverviewResponseTransferToRestResource(WishlistOverviewResponseTransfer $wishlistOverviewResponseTransfer): RestResourceInterface
    {
        $wishlistResource = $this->mapWishlistTransferToRestResource($wishlistOverviewResponseTransfer->getWishlist());
        $this->addWishlistItems($wishlistOverviewResponseTransfer, $wishlistResource);

        return $wishlistResource;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     * @param \Generated\Shared\Transfer\RestWishlistsAttributesTransfer $attributesTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function mapWishlistAttributesToWishlistTransfer(WishlistTransfer $wishlistTransfer, RestWishlistsAttributesTransfer $attributesTransfer): WishlistTransfer
    {
        return $wishlistTransfer->fromArray($attributesTransfer->modifiedToArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistOverviewResponseTransfer $wishlistOverviewResponseTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $wishlistsResource
     *
     * @return void
     */
    protected function addWishlistItems(WishlistOverviewResponseTransfer $wishlistOverviewResponseTransfer, RestResourceInterface $wishlistsResource): void
    {
        foreach ($wishlistOverviewResponseTransfer->getItems() as $itemTransfer) {
            $itemResource = $this->wishlistItemsResourceMapper->mapWishlistItemTransferToRestResource($itemTransfer, $wishlistsResource->getId());
            $wishlistsResource->addRelationship($itemResource);
        }
    }
}
