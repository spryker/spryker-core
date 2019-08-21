<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\WishlistItems;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemMapperInterface;
use Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class WishlistItemAdder implements WishlistItemAdderInterface
{
    protected const SELF_LINK_FORMAT_PATTERN = '%s/%s/%s/%s';

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
     * @var \Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface
     */
    protected $wishlistRestApiClient;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemMapperInterface $wishlistItemMapper
     * @param \Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface $wishlistRestApiClient
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        WishlistItemMapperInterface $wishlistItemMapper,
        WishlistsRestApiClientInterface $wishlistRestApiClient
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->wishlistItemMapper = $wishlistItemMapper;
        $this->wishlistRestApiClient = $wishlistRestApiClient;
    }

    /**
     * @param \Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesRequestTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function add(RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesRequestTransfer, RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        //TODO: structure code better
        $wishlistItemRequest = $this->createWishlistItemRequest($restWishlistItemsAttributesRequestTransfer, $restRequest);
        if (!$wishlistItemRequest) {
            return $this->createWishlistNotFoundErrorResponse($restResponse);
        }

        $wishlistItemResponse = $this->wishlistRestApiClient->addWishlistItem($wishlistItemRequest);

        if (!$wishlistItemResponse->getIsSuccess()) {
            $restErrorMessageTransfer = (new RestErrorMessageTransfer())
                ->setCode(WishlistsRestApiConfig::RESPONSE_CODE_WISHLIST_CANT_ADD_ITEM)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(WishlistsRestApiConfig::RESPONSE_DETAIL_WISHLIST_CANT_ADD_ITEM);

            return $restResponse->addError($restErrorMessageTransfer);
        }

        $restWishlistItemsAttributesTransfer = $this->wishlistItemMapper
            ->mapWishlistItemTransferToRestWishlistItemsAttributes($wishlistItemResponse->getAffectedWishlistItem());

        $wishlistItemResource = $this->restResourceBuilder->createRestResource(
            WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
            $restWishlistItemsAttributesTransfer->getSku(),
            $restWishlistItemsAttributesTransfer
        );
        $wishlistItemResource->addLink(
            RestLinkInterface::LINK_SELF,
            $this->createSelfLinkForWishlistItem($wishlistItemResponse->getWishlist()->getUuid(), $restWishlistItemsAttributesTransfer->getSku())
        );

        return $restResponse->addResource($wishlistItemResource);
    }

    /**
     * @param \Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesRequestTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\WishlistItemRequestTransfer|null
     */
    protected function createWishlistItemRequest(RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesRequestTransfer, RestRequestInterface $restRequest): ?WishlistItemRequestTransfer
    {
        $wishlistResource = $restRequest->findParentResourceByType(WishlistsRestApiConfig::RESOURCE_WISHLISTS);
        if (!$wishlistResource) {
            return null;
        }

        return (new WishlistItemRequestTransfer())
            ->setIdCustomer($restRequest->getRestUser()->getSurrogateIdentifier())
            ->setIdWishlist($wishlistResource->getId())
            ->setSku($restWishlistItemsAttributesRequestTransfer->getSku());
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
     * @param string $wishlistResourceId
     * @param string $wishlistItemResourceId
     *
     * @return string
     */
    protected function createSelfLinkForWishlistItem(string $wishlistResourceId, string $wishlistItemResourceId): string
    {
        return sprintf(
            static::SELF_LINK_FORMAT_PATTERN,
            WishlistsRestApiConfig::RESOURCE_WISHLISTS,
            $wishlistResourceId,
            WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
            $wishlistItemResourceId
        );
    }
}
