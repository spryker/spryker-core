<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\Wishlists;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WishlistOverviewRequestTransfer;
use Generated\Shared\Transfer\WishlistOverviewResponseTransfer;
use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemsResourceMapperInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistsResourceMapperInterface;
use Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistsRestResponseBuilderInterface;
use Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig;

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
     * @var \Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface
     */
    protected $wishlistsRestApiClient;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistsRestResponseBuilderInterface
     */
    protected $wishlistsRestResponseBuilder;

    /**
     * @param \Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface $wishlistClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistsResourceMapperInterface $wishlistsResourceMapper
     * @param \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemsResourceMapperInterface $wishlistsItemResourceMapper
     * @param \Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface $wishlistsRestApiClient
     * @param \Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistsRestResponseBuilderInterface $wishlistsRestResponseBuilder
     */
    public function __construct(
        WishlistsRestApiToWishlistClientInterface $wishlistClient,
        RestResourceBuilderInterface $restResourceBuilder,
        WishlistsResourceMapperInterface $wishlistsResourceMapper,
        WishlistItemsResourceMapperInterface $wishlistsItemResourceMapper,
        WishlistsRestApiClientInterface $wishlistsRestApiClient,
        WishlistsRestResponseBuilderInterface $wishlistsRestResponseBuilder
    ) {
        $this->wishlistClient = $wishlistClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->wishlistsResourceMapper = $wishlistsResourceMapper;
        $this->wishlistsItemResourceMapper = $wishlistsItemResourceMapper;
        $this->wishlistsRestApiClient = $wishlistsRestApiClient;
        $this->wishlistsRestResponseBuilder = $wishlistsRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function findWishlists(RestRequestInterface $restRequest): RestResponseInterface
    {
        $wishlistUuid = $restRequest->getResource()->getId();
        $customerId = $restRequest->getRestUser()->getSurrogateIdentifier();

        if ($wishlistUuid) {
            return $this->getCustomerWishlistByUuid($customerId, $wishlistUuid);
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
        $restResources = [];

        $customerTransfer = (new CustomerTransfer())->setCustomerReference($customerReference);
        $wishlistCollectionTransfer = $this->wishlistClient->getWishlistCollection($customerTransfer);

        $wishlistTransfers = $wishlistCollectionTransfer->getWishlists();

        foreach ($wishlistTransfers as $wishlistTransfer) {
            $restWishlistsAttributesTransfer = $this->wishlistsResourceMapper
                ->mapWishlistTransferToRestWishlistsAttributes($wishlistTransfer);

            $wishlistResource = $this->restResourceBuilder->createRestResource(
                WishlistsRestApiConfig::RESOURCE_WISHLISTS,
                $wishlistTransfer->getUuid(),
                $restWishlistsAttributesTransfer
            );

            $restResources[] = $wishlistResource;
        }

        return $restResources;
    }

    /**
     * @param int $customerId
     * @param string $idWishlist
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCustomerWishlistByUuid(int $customerId, string $idWishlist): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $wishlistRequestTransfer = (new WishlistRequestTransfer())
            ->setIdCustomer($customerId)
            ->setUuid($idWishlist);
        $wishlistResponseTransfer = $this->wishlistsRestApiClient->getCustomerWishlistByUuid($wishlistRequestTransfer);

        if (!$wishlistResponseTransfer->getIsSuccess()) {
            return $restResponse->addError(
                $this->wishlistsRestResponseBuilder->createWishlistNotFoundError()
            );
        }

        $wishlistResource = $this->getWishlistResource($wishlistResponseTransfer);

        return $restResponse->addResource($wishlistResource);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistResponseTransfer $wishlistResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function getWishlistResource(WishlistResponseTransfer $wishlistResponseTransfer): RestResourceInterface
    {
        $restWishlistsAttributesTransfer = $this->wishlistsResourceMapper->mapWishlistTransferToRestWishlistsAttributes($wishlistResponseTransfer->getWishlist());

        $wishlistResource = $this->restResourceBuilder->createRestResource(
            WishlistsRestApiConfig::RESOURCE_WISHLISTS,
            $wishlistResponseTransfer->getWishlist()->getUuid(),
            $restWishlistsAttributesTransfer
        );

        foreach ($wishlistResponseTransfer->getWishlistItems() as $wishlistItemTransfer) {
            $restWishlistsItemAttributesTransfer = $this->wishlistsItemResourceMapper->mapWishlistItemTransferToRestWishlistItemsAttributes($wishlistItemTransfer);

            $wishlistItemResource = $this->restResourceBuilder->createRestResource(
                WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
                $restWishlistsItemAttributesTransfer->getSku(),
                $restWishlistsItemAttributesTransfer
            );

            $wishlistResource->addRelationship($wishlistItemResource);
        }

        return $wishlistResource;
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
