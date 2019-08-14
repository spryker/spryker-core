<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\Wishlists;

use Generated\Shared\Transfer\WishlistRequestTransfer;
use Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface;

class WishlistDeleter implements WishlistDeleterInterface
{
    /**
     * @var \Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface
     */
    protected $wishlistsRestApiClient;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface
     */
    protected $wishlistRestResponseBuilder;

    /**
     * @param \Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface $wishlistsRestApiClient
     * @param \Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface $wishlistRestResponseBuilder
     */
    public function __construct(
        WishlistsRestApiClientInterface $wishlistsRestApiClient,
        WishlistRestResponseBuilderInterface $wishlistRestResponseBuilder
    ) {
        $this->wishlistsRestApiClient = $wishlistsRestApiClient;
        $this->wishlistRestResponseBuilder = $wishlistRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function delete(RestRequestInterface $restRequest): RestResponseInterface
    {
        $wishlistResponseTransfer = $this->wishlistsRestApiClient->deleteWishlist(
            $this->createWishlistRequestTransferFromRequest($restRequest)
        );

        if (!$wishlistResponseTransfer->getIsSuccess()) {
            return $this->wishlistRestResponseBuilder->createErrorResponseFromErrorIdentifier(
                $wishlistResponseTransfer->getErrorIdentifier()
            );
        }

        return $this->wishlistRestResponseBuilder
            ->createWishlistsRestResponse(null);
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
