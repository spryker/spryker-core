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
     * @param \Generated\Shared\Transfer\WishlistOverviewResponseTransfer|null $wishlistOverviewResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapWishlistsResource(WishlistTransfer $wishlistTransfer, ?WishlistOverviewResponseTransfer $wishlistOverviewResponseTransfer = null): RestResourceInterface
    {
        $restWishlistsAttributesTransfer = (new RestWishlistsAttributesTransfer())->fromArray($wishlistTransfer->toArray(), true);

        $wishlistsResource = $this->restResourceBuilder->createRestResource(
            WishlistsRestApiConfig::RESOURCE_WISHLISTS,
            (string)$wishlistTransfer->getUuid(),
            $restWishlistsAttributesTransfer
        );

        if ($wishlistOverviewResponseTransfer !== null) {
            $this->mapWishlistItems($wishlistOverviewResponseTransfer, $wishlistsResource);
        }

        return $wishlistsResource;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistOverviewResponseTransfer $wishlistOverviewResponseTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $wishlistsResource
     *
     * @return void
     */
    protected function mapWishlistItems(WishlistOverviewResponseTransfer $wishlistOverviewResponseTransfer, RestResourceInterface $wishlistsResource): void
    {
        foreach ($wishlistOverviewResponseTransfer->getItems() as $itemTransfer) {
            $itemResource = $this->restResourceBuilder->createRestResource(
                WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
                $itemTransfer->getUuid(),
                $this->wishlistItemsResourceMapper->mapWishlistItemAttributes($itemTransfer)
            );
            $itemResource->addLink(
                'self',
                $this->createSelfLinkForWishlistItem($wishlistsResource->getId(), $itemTransfer->getUuid())
            );

            $wishlistsResource->addRelationship($itemResource);
        }
    }

    /**
     * @param string $wishlistResourceId
     * @param string $wishlistItemResourceId
     *
     * @return string
     */
    protected function createSelfLinkForWishlistItem(string $wishlistResourceId, string $wishlistItemResourceId): string
    {
        return sprintf(
            '%s/%s/%s/%s',
            WishlistsRestApiConfig::RESOURCE_WISHLISTS,
            $wishlistResourceId,
            WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
            $wishlistItemResourceId
        );
    }
}
