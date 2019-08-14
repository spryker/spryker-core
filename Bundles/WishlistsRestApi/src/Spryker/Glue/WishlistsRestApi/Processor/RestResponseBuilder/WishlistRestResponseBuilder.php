<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemMapperInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistMapperInterface;
use Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class WishlistRestResponseBuilder implements WishlistRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig
     */
    protected $wishlistsRestApiConfig;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistMapperInterface
     */
    protected $wishlistMapper;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemMapperInterface
     */
    protected $wishlistItemMapper;

    /**
     * @param \Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig $wishlistsRestApiConfig
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistMapperInterface $wishlistMapper
     * @param \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemMapperInterface $wishlistItemMapper
     */
    public function __construct(
        WishlistsRestApiConfig $wishlistsRestApiConfig,
        RestResourceBuilderInterface $restResourceBuilder,
        WishlistMapperInterface $wishlistMapper,
        WishlistItemMapperInterface $wishlistItemMapper
    ) {
        $this->wishlistsRestApiConfig = $wishlistsRestApiConfig;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->wishlistMapper = $wishlistMapper;
        $this->wishlistItemMapper = $wishlistItemMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer|null $wishlistTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createWishlistsRestResponse(?WishlistTransfer $wishlistTransfer = null): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        if (!$wishlistTransfer) {
            return $restResponse;
        }

        return $restResponse->addResource(
            $this->createWishlistsResource($wishlistTransfer)
        );
    }

    /**
     * @param string $errorIdentifier
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createErrorResponseFromErrorIdentifier(string $errorIdentifier): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse()
            ->addError($this->createRestErrorMessageFromErrorIdentifier($errorIdentifier));
    }

    /**
     * @param string[] $errorIdentifiers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createErrorResponseFromErrorIdentifiers(array $errorIdentifiers): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        foreach ($errorIdentifiers as $errorIdentifier) {
            $restResponse->addError(
                $this->createRestErrorMessageFromErrorIdentifier($errorIdentifier)
            );
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createWishlistItemsResource(WishlistItemTransfer $wishlistItemTransfer): RestResourceInterface
    {
        $restWishlistsItemAttributesTransfer = $this->wishlistItemMapper
            ->mapWishlistItemTransferToRestWishlistItemsAttributes($wishlistItemTransfer);

        return $this->restResourceBuilder->createRestResource(
            WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
            $restWishlistsItemAttributesTransfer->getSku(),
            $restWishlistsItemAttributesTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createWishlistsResource(WishlistTransfer $wishlistTransfer): RestResourceInterface
    {
        $restWishlistsAttributesTransfer = $this->wishlistMapper
            ->mapWishlistTransferToRestWishlistsAttributes($wishlistTransfer);

        $wishlistResource = $this->restResourceBuilder->createRestResource(
            WishlistsRestApiConfig::RESOURCE_WISHLISTS,
            $wishlistTransfer->getUuid(),
            $restWishlistsAttributesTransfer
        );

        foreach ($wishlistTransfer->getWishlistItems() as $wishlistItemTransfer) {
            $wishlistResource->addRelationship(
                $this->createWishlistItemsResource($wishlistItemTransfer)
            );
        }

        return $wishlistResource;
    }

    /**
     * @param string $errorIdentifier
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createRestErrorMessageFromErrorIdentifier(string $errorIdentifier): RestErrorMessageTransfer
    {
        $errorMappingData = $this->wishlistsRestApiConfig->getErrorIdentifierToRestErrorMapping();
        if (!isset($errorMappingData[$errorIdentifier])) {
            return $this->createDefaultUnexpectedRestErrorMessage($errorIdentifier);
        }

        return $this->createRestErrorMessageFromErrorData($errorMappingData[$errorIdentifier]);
    }

    /**
     * @param string $errorIdentifier
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createDefaultUnexpectedRestErrorMessage(string $errorIdentifier): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->setDetail($errorIdentifier);
    }

    /**
     * @param array $errorData
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createRestErrorMessageFromErrorData(array $errorData): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())->fromArray($errorData);
    }
}
