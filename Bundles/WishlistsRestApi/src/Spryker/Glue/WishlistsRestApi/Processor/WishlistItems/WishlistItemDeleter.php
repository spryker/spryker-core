<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\WishlistItems;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistOverviewResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemMapperInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistReaderInterface;
use Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class WishlistItemDeleter implements WishlistItemDeleterInterface
{
    /**
     * @var \Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface
     */
    protected $wishlistClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistReaderInterface
     */
    protected $wishlistReader;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemMapperInterface
     */
    protected $wishlistItemMapper;

    /**
     * @param \Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface $wishlistClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemMapperInterface $wishlistItemMapper
     * @param \Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistReaderInterface $wishlistReader
     */
    public function __construct(
        WishlistsRestApiToWishlistClientInterface $wishlistClient,
        RestResourceBuilderInterface $restResourceBuilder,
        WishlistItemMapperInterface $wishlistItemMapper,
        WishlistReaderInterface $wishlistReader
    ) {
        $this->wishlistClient = $wishlistClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->wishlistItemMapper = $wishlistItemMapper;
        $this->wishlistReader = $wishlistReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function delete(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        if (!$restRequest->getResource()->getId()) {
            return $this->addItemSkuMissingErrorToResponse($restResponse);
        }

        $sku = $restRequest->getResource()->getId();
        $wishlistResource = $restRequest->findParentResourceByType(WishlistsRestApiConfig::RESOURCE_WISHLISTS);
        if (!$wishlistResource) {
            return $this->createWishlistNotFoundErrorResponse($restResponse);
        }

        $wishlistUuid = $wishlistResource->getId();
        $wishlistOverviewTransfer = $this->wishlistReader->findWishlistOverviewByUuid($wishlistUuid);
        if ($wishlistOverviewTransfer === null) {
            return $this->createWishlistNotFoundErrorResponse($restResponse);
        }

        if (!$this->isSkuInWishlist($wishlistOverviewTransfer, $sku)) {
            $restErrorMessageTransfer = (new RestErrorMessageTransfer())
                ->setCode(WishlistsRestApiConfig::RESPONSE_CODE_NO_ITEM_WITH_PROVIDED_ID)
                ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->setDetail(WishlistsRestApiConfig::RESPONSE_DETAIL_NO_ITEM_WITH_PROVIDED_SKU);

            return $restResponse->addError($restErrorMessageTransfer);
        }

        $wishlistItemTransfer = $this->createWishlistItemTransfer($wishlistOverviewTransfer->getWishlist(), $sku);
        $this->wishlistClient->removeItem($wishlistItemTransfer);

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     * @param string|null $sku
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    protected function createWishlistItemTransfer(WishlistTransfer $wishlistTransfer, ?string $sku = null): WishlistItemTransfer
    {
        $wishlistItemTransfer = new WishlistItemTransfer();
        $wishlistItemTransfer->setFkWishlist($wishlistTransfer->getIdWishlist());
        $wishlistItemTransfer->setWishlistName($wishlistTransfer->getName());
        $wishlistItemTransfer->setFkCustomer($wishlistTransfer->getFkCustomer());
        $wishlistItemTransfer->setSku($sku);

        return $wishlistItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistOverviewResponseTransfer $wishlistOverviewTransfer
     * @param string $sku
     *
     * @return bool
     */
    protected function isSkuInWishlist(WishlistOverviewResponseTransfer $wishlistOverviewTransfer, string $sku): bool
    {
        foreach ($wishlistOverviewTransfer->getItems() as $wishlistItem) {
            if ($wishlistItem->getSku() === $sku) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createWishlistNotFoundErrorResponse(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(WishlistsRestApiConfig::RESPONSE_CODE_WISHLIST_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(WishlistsRestApiConfig::RESPONSE_DETAIL_WISHLIST_NOT_FOUND);

        return $restResponse->addError($restErrorMessageTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addItemSkuMissingErrorToResponse(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(WishlistsRestApiConfig::RESPONSE_CODE_ID_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(WishlistsRestApiConfig::RESPONSE_DETAIL_ID_IS_NOT_SPECIFIED);

        return $restResponse->addError($restErrorMessageTransfer);
    }
}
