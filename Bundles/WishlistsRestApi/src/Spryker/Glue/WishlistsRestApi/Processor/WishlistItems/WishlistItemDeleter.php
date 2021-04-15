<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\WishlistItems;

use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface;
use Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig;

class WishlistItemDeleter implements WishlistItemDeleterInterface
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
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function delete(RestRequestInterface $restRequest): RestResponseInterface
    {
        if (!$restRequest->getResource()->getId()) {
            return $this->wishlistRestResponseBuilder->createItemSkuMissingErrorToResponse();
        }

        if (!$restRequest->findParentResourceByType(WishlistsRestApiConfig::RESOURCE_WISHLISTS)) {
            return $this->wishlistRestResponseBuilder->createWishlistNotFoundErrorResponse();
        }

        $deleteWishlistItemResponse = $this->wishlistRestApiClient->deleteWishlistItem(
            $this->createWishlistItemRequest($restRequest)
        );

        if (!$deleteWishlistItemResponse->getIsSuccess()) {
            /** @var string $errorIdentifier */
            $errorIdentifier = $deleteWishlistItemResponse->getErrorIdentifier();

            return $this->wishlistRestResponseBuilder->createErrorResponseFromErrorIdentifier($errorIdentifier);
        }

        return $this->wishlistRestResponseBuilder->createEmptyResponse();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\WishlistItemRequestTransfer
     */
    protected function createWishlistItemRequest(RestRequestInterface $restRequest): WishlistItemRequestTransfer
    {
        /** @var string $idResource */
        $idResource = $restRequest->getResource()->getId();
        /** @var \Generated\Shared\Transfer\RestUserTransfer $restUser */
        $restUser = $restRequest->getRestUser();
        /** @var int $surrogateIdentifier */
        $surrogateIdentifier = $restUser->getSurrogateIdentifier();

        /** @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource */
        $resource = $restRequest->findParentResourceByType(WishlistsRestApiConfig::RESOURCE_WISHLISTS);
        /** @var string $wishlistUuid */
        $wishlistUuid = $resource->getId();

        return (new WishlistItemRequestTransfer())
            ->setUuid($idResource)
            ->setUuidWishlist($wishlistUuid)
            ->setIdCustomer($surrogateIdentifier);
    }
}
