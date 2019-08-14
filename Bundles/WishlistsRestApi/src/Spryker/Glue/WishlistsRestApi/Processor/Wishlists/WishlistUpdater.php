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
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistResourceMapperInterface;
use Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface;

class WishlistUpdater implements WishlistUpdaterInterface
{
    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistResourceMapperInterface
     */
    protected $wishlistResourceMapper;

    /**
     * @var \Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface
     */
    protected $wishlistsRestApiClient;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface
     */
    protected $wishlistRestResponseBuilder;

    /**
     * @param \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistResourceMapperInterface $wishlistResourceMapper
     * @param \Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface $wishlistsRestApiClient
     * @param \Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface $wishlistRestResponseBuilder
     */
    public function __construct(
        WishlistResourceMapperInterface $wishlistResourceMapper,
        WishlistsRestApiClientInterface $wishlistsRestApiClient,
        WishlistRestResponseBuilderInterface $wishlistRestResponseBuilder
    ) {
        $this->wishlistResourceMapper = $wishlistResourceMapper;
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
        $wishlistTransfer = $this->wishlistResourceMapper->mapWishlistAttributesToWishlistTransfer(new WishlistTransfer(), $attributesTransfer);

        $wishlistRequestTransfer = $this->createWishlistRequestTransferFromRequest($restRequest)
            ->setWishlist($wishlistTransfer);

        $wishlistResponseTransfer = $this->wishlistsRestApiClient->updateWishlist($wishlistRequestTransfer);
        if (!$wishlistResponseTransfer->getIsSuccess()) {
            return $this->wishlistRestResponseBuilder->createErrorResponseFromErrorIdentifier(
                $wishlistResponseTransfer->getErrorIdentifier()
            );
        }

        return $this->wishlistRestResponseBuilder
            ->createWishlistsRestResponse($wishlistResponseTransfer->getWishlist());
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\WishlistRequestTransfer
     */
    protected function createWishlistRequestTransferFromRequest(RestRequestInterface $restRequest)
    {
        return (new WishlistRequestTransfer())
            ->setUuid($restRequest->getResource()->getId())
            ->setIdCustomer($restRequest->getRestUser()->getSurrogateIdentifier());
    }
}
