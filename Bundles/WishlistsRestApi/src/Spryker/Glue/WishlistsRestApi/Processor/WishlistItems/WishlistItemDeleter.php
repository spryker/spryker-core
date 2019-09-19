<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\WishlistItems;

use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
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

        $wishlistResource = $restRequest->findParentResourceByType(WishlistsRestApiConfig::RESOURCE_WISHLISTS);
        if (!$wishlistResource) {
            return $this->wishlistRestResponseBuilder->createWishlistNotFoundErrorResponse();
        }

        $deleteWishlistItemResponse = $this->wishlistRestApiClient->deleteWishlistItem(
            $this->createWishlistItemRequest($restRequest, $wishlistResource)
        );

        if (!$deleteWishlistItemResponse->getIsSuccess()) {
            return $this->wishlistRestResponseBuilder->createErrorResponseFromErrorIdentifier(
                $deleteWishlistItemResponse->getErrorIdentifier()
            );
        }

        return $this->wishlistRestResponseBuilder->createEmptyResponse();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $wishlistResource
     *
     * @return \Generated\Shared\Transfer\WishlistItemRequestTransfer
     */
    protected function createWishlistItemRequest(RestRequestInterface $restRequest, RestResourceInterface $wishlistResource): WishlistItemRequestTransfer
    {
        $sku = $restRequest->getResource()->getId();
        $idCustomer = $restRequest->getRestUser()->getSurrogateIdentifier();
        $uuidWishlist = $wishlistResource->getId();

        return (new WishlistItemRequestTransfer())
            ->setSku($sku)
            ->setUuidWishlist($uuidWishlist)
            ->setIdCustomer($idCustomer);
    }
}
