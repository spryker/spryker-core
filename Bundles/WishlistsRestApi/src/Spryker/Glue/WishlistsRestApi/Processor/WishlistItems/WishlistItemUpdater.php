<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\WishlistItems;

use Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemMapperInterface;
use Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface;
use Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig;

class WishlistItemUpdater implements WishlistItemUpdaterInterface
{
    /**
     * @var \Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface
     */
    protected $wishlistRestApiClient;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface
     */
    protected $wishlistRestResponseBuilder;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemMapperInterface
     */
    protected $wishlistItemMapper;

    /**
     * @param \Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface $wishlistRestApiClient
     * @param \Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface $wishlistRestResponseBuilder
     * @param \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemMapperInterface $wishlistItemMapper
     */
    public function __construct(
        WishlistsRestApiClientInterface $wishlistRestApiClient,
        WishlistRestResponseBuilderInterface $wishlistRestResponseBuilder,
        WishlistItemMapperInterface $wishlistItemMapper
    ) {
        $this->wishlistRestApiClient = $wishlistRestApiClient;
        $this->wishlistRestResponseBuilder = $wishlistRestResponseBuilder;
        $this->wishlistItemMapper = $wishlistItemMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function update(
        RestRequestInterface $restRequest,
        RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer
    ): RestResponseInterface {
        if (!$restRequest->getResource()->getId()) {
            return $this->wishlistRestResponseBuilder->createItemSkuMissingErrorToResponse();
        }

        $wishlistResource = $restRequest->findParentResourceByType(WishlistsRestApiConfig::RESOURCE_WISHLISTS);

        if (!$wishlistResource || $wishlistResource->getId() === null) {
            return $this->wishlistRestResponseBuilder->createWishlistNotFoundErrorResponse();
        }

        $wishlistItemRequestTransfer = $this->createWishlistItemRequest(
            $restRequest,
            $wishlistResource,
            $restWishlistItemsAttributesTransfer,
        );

        $wishlistItemResponseTransfer = $this->wishlistRestApiClient->updateWishlistItem($wishlistItemRequestTransfer);

        if ($wishlistItemResponseTransfer->getIsSuccess()) {
            return $this->wishlistRestResponseBuilder->createWishlistItemsRestResponse(
                $wishlistResource->getId(),
                $wishlistItemResponseTransfer->getWishlistItem(),
            );
        }

        if ($wishlistItemResponseTransfer->getErrorIdentifier() === null) {
            return $this->wishlistRestResponseBuilder->createUnknownErrorResponse();
        }

        return $this->wishlistRestResponseBuilder->createErrorResponseFromErrorIdentifier($wishlistItemResponseTransfer->getErrorIdentifier());
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $wishlistResource
     * @param \Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemRequestTransfer
     */
    protected function createWishlistItemRequest(
        RestRequestInterface $restRequest,
        RestResourceInterface $wishlistResource,
        RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer
    ): WishlistItemRequestTransfer {
        /** @var \Generated\Shared\Transfer\RestUserTransfer $restUser */
        $restUser = $restRequest->getRestUser();

        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setIdCustomer($restUser->getSurrogateIdentifier())
            ->setUuidWishlist($wishlistResource->getId())
            ->setUuid($restRequest->getResource()->getId());

        return $this->wishlistItemMapper->mapRestWishlistItemsAttributesToWishlistItemRequest(
            $restWishlistItemsAttributesTransfer,
            $wishlistItemRequestTransfer,
        );
    }
}
