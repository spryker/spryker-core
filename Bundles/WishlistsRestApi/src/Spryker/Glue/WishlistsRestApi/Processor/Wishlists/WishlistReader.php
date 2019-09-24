<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\Wishlists;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WishlistFilterTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface;
use Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface;

class WishlistReader implements WishlistReaderInterface
{
    /**
     * @var \Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface
     */
    protected $wishlistClient;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface
     */
    protected $wishlistRestResponseBuilder;

    /**
     * @param \Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface $wishlistClient
     * @param \Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface $wishlistRestResponseBuilder
     */
    public function __construct(
        WishlistsRestApiToWishlistClientInterface $wishlistClient,
        WishlistRestResponseBuilderInterface $wishlistRestResponseBuilder
    ) {
        $this->wishlistClient = $wishlistClient;
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
            return $this->getCustomerWishlistByUuid($customerId, $wishlistUuid);
        }

        return $this->getCustomerWishlists($restRequest->getRestUser()->getNaturalIdentifier());
    }

    /**
     * @param string $customerReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function getWishlistsByCustomerReference(string $customerReference): array
    {
        $wishlistCollectionTransfer = $this->wishlistClient
            ->getWishlistCollection($this->createCustomerTransfer($customerReference));

        $restResources = [];
        foreach ($wishlistCollectionTransfer->getWishlists() as $wishlistTransfer) {
            $restResources[] = $this->wishlistRestResponseBuilder->createWishlistsResource($wishlistTransfer);
        }

        return $restResources;
    }

    /**
     * @param int $idCustomer
     * @param string $uuidWishlist
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getCustomerWishlistByUuid(int $idCustomer, string $uuidWishlist): RestResponseInterface
    {
        $wishlistResponseTransfer = $this->wishlistClient
            ->getWishlistByFilter($this->createWishlistFilterTransfer($idCustomer, $uuidWishlist));

        if (!$wishlistResponseTransfer->getIsSuccess()) {
            return $this->wishlistRestResponseBuilder->createRestErrorResponse($wishlistResponseTransfer->getErrors());
        }

        return $this->wishlistRestResponseBuilder
            ->createWishlistsRestResponse($wishlistResponseTransfer->getWishlist());
    }

    /**
     * @param string $customerReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getCustomerWishlists(string $customerReference): RestResponseInterface
    {
        $customerWishlistCollectionTransfer = $this->wishlistClient
            ->getWishlistCollection($this->createCustomerTransfer($customerReference));

        return $this->wishlistRestResponseBuilder->createWishlistCollectionResponse($customerWishlistCollectionTransfer);
    }

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransfer(string $customerReference): CustomerTransfer
    {
        return (new CustomerTransfer())->setCustomerReference($customerReference);
    }

    /**
     * @param int $idCustomer
     * @param string $uuidWishlist
     *
     * @return \Generated\Shared\Transfer\WishlistFilterTransfer
     */
    protected function createWishlistFilterTransfer(int $idCustomer, string $uuidWishlist): WishlistFilterTransfer
    {
        return (new WishlistFilterTransfer())
            ->setIdCustomer($idCustomer)
            ->setUuid($uuidWishlist);
    }
}
