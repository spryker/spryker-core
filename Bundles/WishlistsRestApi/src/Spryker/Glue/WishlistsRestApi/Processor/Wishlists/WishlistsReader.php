<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\Wishlists;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\WishlistOverviewRequestTransfer;
use Generated\Shared\Transfer\WishlistOverviewResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemsResourceMapperInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistsResourceMapperInterface;
use Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class WishlistsReader implements WishlistsReaderInterface
{
    /**
     * @var \Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface
     */
    protected $wishlistClient;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistsResourceMapperInterface
     */
    protected $wishlistsResourceMapper;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemsResourceMapperInterface
     */
    protected $wishlistsItemResourceMapper;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface $wishlistClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistsResourceMapperInterface $wishlistsResourceMapper
     * @param \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemsResourceMapperInterface $wishlistsItemResourceMapper
     */
    public function __construct(
        WishlistsRestApiToWishlistClientInterface $wishlistClient,
        RestResourceBuilderInterface $restResourceBuilder,
        WishlistsResourceMapperInterface $wishlistsResourceMapper,
        WishlistItemsResourceMapperInterface $wishlistsItemResourceMapper
    ) {
        $this->wishlistClient = $wishlistClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->wishlistsResourceMapper = $wishlistsResourceMapper;
        $this->wishlistsItemResourceMapper = $wishlistsItemResourceMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function findWishlists(RestRequestInterface $restRequest): RestResponseInterface
    {
        $wishlistUuid = $restRequest->getResource()->getId();

        if ($wishlistUuid) {
            return $this->readByIdentifier($wishlistUuid);
        }

        return $this->readCurrentCustomerWishlists();
    }

    /**
     * @param string $wishlistUuid
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer|null
     */
    public function findWishlistByUuid(string $wishlistUuid): ?WishlistTransfer
    {
        return $this->getWishlistByUuid($wishlistUuid);
    }

    /**
     * @param string $wishlistUuid
     *
     * @return \Generated\Shared\Transfer\WishlistOverviewResponseTransfer|null
     */
    public function findWishlistOverviewByUuid(string $wishlistUuid): ?WishlistOverviewResponseTransfer
    {
        $wishlistTransfer = $this->getWishlistByUuid($wishlistUuid);
        if (!$wishlistTransfer) {
            return null;
        }

        return $this->getWishlistOverviewWithoutProductDetails($wishlistTransfer);
    }

    /**
     * @param string $customerReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function getWishlistsByCustomerReference(string $customerReference): array
    {
        $response = [];

        $customerTransfer = (new CustomerTransfer())->setCustomerReference($customerReference);
        $customerWishlistCollectionTransfer = $this->wishlistClient->getWishlistCollection($customerTransfer);

        $customerWishlists = $customerWishlistCollectionTransfer->getWishlists();

        foreach ($customerWishlists as $wishlistTransfer) {
            $restWishlistsAttributes = $this->wishlistsResourceMapper
                ->mapWishlistTransferToRestWishlistsAttributes($wishlistTransfer);

            $wishlistResource = $this->restResourceBuilder->createRestResource(
                WishlistsRestApiConfig::RESOURCE_WISHLISTS,
                $restWishlistsAttributes->getName(),
                $restWishlistsAttributes
            );

            $response[] = $wishlistResource;
        }

        return $response;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function getWishistResource(WishlistTransfer $wishlistTransfer): RestResourceInterface
    {
        $wishlistOverviewResponseTransfer = $this->getWishlistOverviewWithoutProductDetails($wishlistTransfer);

        $restWishlistsAttributesTransfer = $this->wishlistsResourceMapper->mapWishlistTransferToRestWishlistsAttributes($wishlistOverviewResponseTransfer->getWishlist());

        $wishlistResource = $this->restResourceBuilder->createRestResource(
            WishlistsRestApiConfig::RESOURCE_WISHLISTS,
            $wishlistTransfer->getUuid(),
            $restWishlistsAttributesTransfer
        );

        foreach ($wishlistOverviewResponseTransfer->getItems() as $itemTransfer) {
            $restWishlistsItemAttributesTransfer = $this->wishlistsItemResourceMapper->mapWishlistItemTransferToRestWishlistItemsAttributes($itemTransfer);

            $itemResource = $this->restResourceBuilder->createRestResource(
                WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
                $restWishlistsItemAttributesTransfer->getSku(),
                $restWishlistsItemAttributesTransfer
            );

            $wishlistResource->addRelationship($itemResource);
        }

        return $wishlistResource;
    }

    /**
     * @param string $idWishlist
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function readByIdentifier(string $idWishlist): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $wishlistTransfer = $this->findWishlistByUuid($idWishlist);
        if ($wishlistTransfer === null) {
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setCode(WishlistsRestApiConfig::RESPONSE_CODE_WISHLIST_NOT_FOUND)
                ->setStatus(Response::HTTP_NOT_FOUND)
                ->setDetail(WishlistsRestApiConfig::RESPONSE_DETAIL_WISHLIST_NOT_FOUND);

            return $restResponse->addError($restErrorTransfer);
        }

        $wishlistResource = $this->getWishistResource($wishlistTransfer);

        return $restResponse->addResource($wishlistResource);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function readCurrentCustomerWishlists(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $customerWishlistCollectionTransfer = $this->wishlistClient->getCustomerWishlistCollection();
        $customerWishlists = $customerWishlistCollectionTransfer->getWishlists();

        foreach ($customerWishlists as $wishlistTransfer) {
            $restWishlistsAttributesTransfer = $this->wishlistsResourceMapper->mapWishlistTransferToRestWishlistsAttributes($wishlistTransfer);

            $wishlistResource = $this->restResourceBuilder->createRestResource(
                WishlistsRestApiConfig::RESOURCE_WISHLISTS,
                $wishlistTransfer->getUuid(),
                $restWishlistsAttributesTransfer
            );

            $restResponse->addResource($wishlistResource);
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistOverviewResponseTransfer
     */
    protected function getWishlistOverviewWithoutProductDetails(WishlistTransfer $wishlistTransfer): WishlistOverviewResponseTransfer
    {
        $wishlistOverviewRequestTransfer = new WishlistOverviewRequestTransfer();
        $wishlistOverviewRequestTransfer->setWishlist($wishlistTransfer);
        $wishlistOverviewRequestTransfer->setPage(0);
        $wishlistOverviewRequestTransfer->setItemsPerPage(PHP_INT_MAX);

        return $this->wishlistClient->getWishlistOverviewWithoutProductDetails($wishlistOverviewRequestTransfer);
    }

    /**
     * @param string $wishlistUuid
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer|null
     */
    protected function getWishlistByUuid(string $wishlistUuid): ?WishlistTransfer
    {
        $customerWishlistCollectionTransfer = $this->wishlistClient->getCustomerWishlistCollection();
        $customerWishlists = $customerWishlistCollectionTransfer->getWishlists();

        foreach ($customerWishlists as $wishlistTransfer) {
            if ($wishlistTransfer->getUuid() === $wishlistUuid) {
                return $wishlistTransfer;
            }
        }

        return null;
    }
}
