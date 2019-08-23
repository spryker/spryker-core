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
use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemMapperInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistMapperInterface;
use Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface;
use Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig;

class WishlistReader implements WishlistReaderInterface
{
    /**
     * @var \Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface
     */
    protected $wishlistClient;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistMapperInterface
     */
    protected $wishlistResourceMapper;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemMapperInterface
     */
    protected $wishlistItemResourceMapper;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface
     */
    protected $wishlistsRestApiClient;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface
     */
    protected $wishlistRestResponseBuilder;

    /**
     * @param \Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface $wishlistClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistMapperInterface $wishlistResourceMapper
     * @param \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemMapperInterface $wishlistItemResourceMapper
     * @param \Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface $wishlistsRestApiClient
     * @param \Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface $wishlistRestResponseBuilder
     */
    public function __construct(
        WishlistsRestApiToWishlistClientInterface $wishlistClient,
        RestResourceBuilderInterface $restResourceBuilder,
        WishlistMapperInterface $wishlistResourceMapper,
        WishlistItemMapperInterface $wishlistItemResourceMapper,
        WishlistsRestApiClientInterface $wishlistsRestApiClient,
        WishlistRestResponseBuilderInterface $wishlistRestResponseBuilder
    ) {
        $this->wishlistClient = $wishlistClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->wishlistResourceMapper = $wishlistResourceMapper;
        $this->wishlistItemResourceMapper = $wishlistItemResourceMapper;
        $this->wishlistsRestApiClient = $wishlistsRestApiClient;
        $this->wishlistRestResponseBuilder = $wishlistRestResponseBuilder;
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
            return $this->getWishlistResponseByIdCustomerAndUuid($customerId, $wishlistUuid);
        }

        return $this->readCurrentCustomerWishlists();
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
            $restWishlistsAttributesTransfer = $this->wishlistResourceMapper
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
     * @param string $uuidWishlist
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getWishlistResponseByIdCustomerAndUuid(int $customerId, string $uuidWishlist): RestResponseInterface
    {
        $wishlistRequestTransfer = (new WishlistRequestTransfer())
            ->setIdCustomer($customerId)
            ->setIdWishlist($uuidWishlist);
        $wishlistResponseTransfer = $this->wishlistClient->getWishlistByIdCustomerAndUuid($wishlistRequestTransfer);

        //TODO: add proper error
        if (!$wishlistResponseTransfer->getIsSuccess()) {
            return $this->restResourceBuilder->createRestResponse()
                ->addError((new RestErrorMessageTransfer())
                    ->setDetail('')
                    ->setCode('200')
                    ->setStatus(422));
        }

        return $this->wishlistRestResponseBuilder
            ->createWishlistsRestResponse($wishlistResponseTransfer->getWishlist());
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
            $restWishlistsAttributesTransfer = $this->wishlistResourceMapper->mapWishlistTransferToRestWishlistsAttributes($wishlistTransfer);

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
}
