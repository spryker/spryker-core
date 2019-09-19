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
use Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface;
use Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig;

class WishlistItemAdder implements WishlistItemAdderInterface
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
     * @param \Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface $wishlistRestApiClient
     * @param \Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface $wishlistRestResponseBuilder
     */
    public function __construct(
        WishlistsRestApiClientInterface $wishlistRestApiClient,
        WishlistRestResponseBuilderInterface $wishlistRestResponseBuilder
    ) {
        $this->wishlistRestApiClient = $wishlistRestApiClient;
        $this->wishlistRestResponseBuilder = $wishlistRestResponseBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesRequestTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function add(RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesRequestTransfer, RestRequestInterface $restRequest): RestResponseInterface
    {
        $wishlistResource = $restRequest->findParentResourceByType(WishlistsRestApiConfig::RESOURCE_WISHLISTS);
        if (!$wishlistResource) {
            return $this->wishlistRestResponseBuilder->createWishlistNotFoundErrorResponse();
        }

        $wishlistItemRequest = $this->createWishlistItemRequest(
            $restRequest,
            $wishlistResource,
            $restWishlistItemsAttributesRequestTransfer
        );
        $wishlistItemResponse = $this->wishlistRestApiClient->addWishlistItem($wishlistItemRequest);

        if (!$wishlistItemResponse->getIsSuccess()) {
            return $this->wishlistRestResponseBuilder->createCantAddWishlistItemErrorResponse();
        }

        return $this->wishlistRestResponseBuilder
            ->createWishlistItemsRestResponse($wishlistResource->getId(), $wishlistItemResponse->getWishlistItem());
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $wishlistResource
     * @param \Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemRequestTransfer
     */
    protected function createWishlistItemRequest(
        RestRequestInterface $restRequest,
        RestResourceInterface $wishlistResource,
        RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesRequestTransfer
    ): WishlistItemRequestTransfer {
        return (new WishlistItemRequestTransfer())
            ->setIdCustomer($restRequest->getRestUser()->getSurrogateIdentifier())
            ->setUuidWishlist($wishlistResource->getId())
            ->setSku($restWishlistItemsAttributesRequestTransfer->getSku());
    }
}
