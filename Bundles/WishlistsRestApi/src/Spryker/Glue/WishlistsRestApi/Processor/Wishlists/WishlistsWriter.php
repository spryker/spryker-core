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
use Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistsResourceMapperInterface;
use Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistsRestResponseBuilderInterface;

class WishlistsWriter implements WishlistsWriterInterface
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
     * @var \Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface
     */
    protected $wishlistsRestApiClient;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistsRestResponseBuilderInterface
     */
    protected $wishlistsRestResponseBuilder;

    /**
     * @param \Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface $wishlistClient
     * @param \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistsResourceMapperInterface $wishlistsResourceMapper
     * @param \Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface $wishlistsRestApiClient
     * @param \Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistsRestResponseBuilderInterface $wishlistsRestResponseBuilder
     */
    public function __construct(
        WishlistsRestApiToWishlistClientInterface $wishlistClient,
        WishlistsResourceMapperInterface $wishlistsResourceMapper,
        WishlistsRestApiClientInterface $wishlistsRestApiClient,
        WishlistsRestResponseBuilderInterface $wishlistsRestResponseBuilder
    ) {
        $this->wishlistClient = $wishlistClient;
        $this->wishlistsResourceMapper = $wishlistsResourceMapper;
        $this->wishlistsRestApiClient = $wishlistsRestApiClient;
        $this->wishlistsRestResponseBuilder = $wishlistsRestResponseBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\RestWishlistsAttributesTransfer $attributesTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function create(RestWishlistsAttributesTransfer $attributesTransfer, RestRequestInterface $restRequest): RestResponseInterface
    {
        $wishlistTransfer = $this->wishlistsResourceMapper->mapWishlistAttributesToWishlistTransfer(new WishlistTransfer(), $attributesTransfer);
        $wishlistTransfer->setFkCustomer((int)$restRequest->getRestUser()->getSurrogateIdentifier());

        $wishlistResponseTransfer = $this->wishlistClient->validateAndCreateWishlist($wishlistTransfer);
        if (!$wishlistResponseTransfer->getIsSuccess()) {
            return $this->wishlistsRestResponseBuilder->createErrorResponseFromZedErrors(
                $wishlistResponseTransfer->getErrors()
            );
        }

        return $this->wishlistsRestResponseBuilder
            ->createWishlistsRestResponse($wishlistResponseTransfer->getWishlist());
    }

    /**
     * @param \Generated\Shared\Transfer\RestWishlistsAttributesTransfer $attributesTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function update(RestWishlistsAttributesTransfer $attributesTransfer, RestRequestInterface $restRequest): RestResponseInterface
    {
        $wishlistTransfer = $this->wishlistsResourceMapper->mapWishlistAttributesToWishlistTransfer(new WishlistTransfer(), $attributesTransfer);

        $wishlistRequestTransfer = $this->createWishlistRequestTransferFromRequest($restRequest)
            ->setWishlist($wishlistTransfer);

        $wishlistResponseTransfer = $this->wishlistsRestApiClient->updateWishlist($wishlistRequestTransfer);
        if (!$wishlistResponseTransfer->getIsSuccess()) {
            return $this->wishlistsRestResponseBuilder->createErrorResponseFromErrorIdentifier(
                $wishlistResponseTransfer->getErrorIdentifier()
            );
        }

        return $this->wishlistsRestResponseBuilder
            ->createWishlistsRestResponse($wishlistResponseTransfer->getWishlist());
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
            return $this->wishlistsRestResponseBuilder->createErrorResponseFromErrorIdentifier(
                $wishlistResponseTransfer->getErrorIdentifier()
            );
        }

        return $this->wishlistsRestResponseBuilder
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
