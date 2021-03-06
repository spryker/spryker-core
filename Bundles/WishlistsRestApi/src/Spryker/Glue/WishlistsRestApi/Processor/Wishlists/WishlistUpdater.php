<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\Wishlists;

use Generated\Shared\Transfer\RestWishlistsAttributesTransfer;
use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistMapperInterface;
use Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface;

class WishlistUpdater implements WishlistUpdaterInterface
{
    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistMapperInterface
     */
    protected $wishlistMapper;

    /**
     * @var \Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface
     */
    protected $wishlistsRestApiClient;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface
     */
    protected $wishlistRestResponseBuilder;

    /**
     * @param \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistMapperInterface $wishlistMapper
     * @param \Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface $wishlistsRestApiClient
     * @param \Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface $wishlistRestResponseBuilder
     */
    public function __construct(
        WishlistMapperInterface $wishlistMapper,
        WishlistsRestApiClientInterface $wishlistsRestApiClient,
        WishlistRestResponseBuilderInterface $wishlistRestResponseBuilder
    ) {
        $this->wishlistMapper = $wishlistMapper;
        $this->wishlistsRestApiClient = $wishlistsRestApiClient;
        $this->wishlistRestResponseBuilder = $wishlistRestResponseBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\RestWishlistsAttributesTransfer $attributesTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function update(RestWishlistsAttributesTransfer $attributesTransfer, RestRequestInterface $restRequest): RestResponseInterface
    {
        $wishlistRequestTransfer = $this->createWishlistRequestTransferFromRequest($attributesTransfer, $restRequest);

        $wishlistResponseTransfer = $this->wishlistsRestApiClient->updateWishlist($wishlistRequestTransfer);
        if (!$wishlistResponseTransfer->getIsSuccess()) {
            /** @var string $errorIdentifier */
            $errorIdentifier = $wishlistResponseTransfer->getErrorIdentifier();

            return $this->wishlistRestResponseBuilder->createErrorResponseFromErrorIdentifier($errorIdentifier);
        }

        return $this->wishlistRestResponseBuilder
            ->createWishlistsRestResponse($wishlistResponseTransfer->getWishlist());
    }

    /**
     * @param \Generated\Shared\Transfer\RestWishlistsAttributesTransfer $restWishlistsAttributesTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\WishlistRequestTransfer
     */
    protected function createWishlistRequestTransferFromRequest(
        RestWishlistsAttributesTransfer $restWishlistsAttributesTransfer,
        RestRequestInterface $restRequest
    ) {
        $wishlistTransfer = $this->wishlistMapper
            ->mapWishlistAttributesToWishlistTransfer($restWishlistsAttributesTransfer, new WishlistTransfer());

        /** @var \Generated\Shared\Transfer\RestUserTransfer $restUser */
        $restUser = $restRequest->getRestUser();
        /** @var int $customerId */
        $customerId = $restUser->getSurrogateIdentifier();

        return (new WishlistRequestTransfer())
            ->setUuid($restRequest->getResource()->getId())
            ->setIdCustomer($customerId)
            ->setWishlist($wishlistTransfer);
    }
}
