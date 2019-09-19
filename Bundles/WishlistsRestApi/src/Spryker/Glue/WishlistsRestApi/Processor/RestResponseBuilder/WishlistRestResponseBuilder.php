<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer;
use Generated\Shared\Transfer\WishlistCollectionTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemMapperInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistMapperInterface;
use Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class WishlistRestResponseBuilder implements WishlistRestResponseBuilderInterface
{
    protected const SELF_LINK_FORMAT_PATTERN = '%s/%s/%s/%s';

    /**
     * @uses \Spryker\Zed\Wishlist\Business\Model\Writer::ERROR_MESSAGE_NAME_ALREADY_EXISTS
     */
    protected const ERROR_MESSAGE_NAME_ALREADY_EXISTS = 'wishlist.validation.error.name.already_exists';

    /**
     * @uses \Spryker\Zed\Wishlist\Business\Model\Writer::ERROR_MESSAGE_NAME_HAS_INCORRECT_FORMAT
     */
    protected const ERROR_MESSAGE_NAME_HAS_INCORRECT_FORMAT = 'wishlist.validation.error.name.wrong_format';

    /**
     * @uses \Spryker\Zed\Wishlist\Business\Model\Reader::ERROR_MESSAGE_WISHLIST_NOT_FOUND
     */
    protected const ERROR_MESSAGE_WISHLIST_NOT_FOUND = 'wishlist.not.found';

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
     * @param string $idWishlist
     * @param \Generated\Shared\Transfer\WishlistItemTransfer|null $wishlistItemTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createWishlistItemsRestResponse(string $idWishlist, ?WishlistItemTransfer $wishlistItemTransfer = null): RestResponseInterface
    {
        $restWishlistItemsAttributesTransfer = $this->wishlistItemMapper
            ->mapWishlistItemTransferToRestWishlistItemsAttributes($wishlistItemTransfer, new RestWishlistItemsAttributesTransfer());

        $wishlistItemResource = $this->restResourceBuilder->createRestResource(
            WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
            $restWishlistItemsAttributesTransfer->getSku(),
            $restWishlistItemsAttributesTransfer
        );
        $wishlistItemResource->addLink(
            RestLinkInterface::LINK_SELF,
            $this->createSelfLinkForWishlistItem($idWishlist, $restWishlistItemsAttributesTransfer->getSku())
        );

        return $this->restResourceBuilder
            ->createRestResponse()
            ->addResource($wishlistItemResource);
    }

    /**
     * @param string|null $errorIdentifier
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createErrorResponseFromErrorIdentifier(?string $errorIdentifier): RestResponseInterface
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
            ->mapWishlistItemTransferToRestWishlistItemsAttributes($wishlistItemTransfer, new RestWishlistItemsAttributesTransfer());

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
     * @param \Generated\Shared\Transfer\WishlistCollectionTransfer $wishlistCollectionTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createWishlistCollectionResponse(WishlistCollectionTransfer $wishlistCollectionTransfer): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($wishlistCollectionTransfer->getWishlists() as $wishlistTransfer) {
            $restResponse->addResource($this->createWishlistsResource($wishlistTransfer));
        }

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createEmptyResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @param \Generated\Shared\Transfer\RestErrorMessageTransfer $errorMessage
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createErrorResponseFromErrorMessage(RestErrorMessageTransfer $errorMessage): RestResponseInterface
    {
        return $this->restResourceBuilder
            ->createRestResponse()
            ->addError($errorMessage);
    }

    /**
     * @param array $errors
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createRestErrorResponse(array $errors): RestResponseInterface
    {
        foreach ($errors as $error) {
            if ($error === static::ERROR_MESSAGE_NAME_ALREADY_EXISTS) {
                return $this->createWishlistAlreadyExistsErrorResponse();
            }

            if ($error === static::ERROR_MESSAGE_NAME_HAS_INCORRECT_FORMAT) {
                return $this->createWishlistNameInvalidErrorResponse();
            }

            if ($error === static::ERROR_MESSAGE_WISHLIST_NOT_FOUND) {
                return $this->createWishlistNotFoundErrorResponse();
            }
        }

        return $this->createUnknownErrorResponse();
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createUnknownErrorResponse(): RestResponseInterface
    {
        $errorMessage = (new RestErrorMessageTransfer())
            ->setDetail(WishlistsRestApiConfig::RESPONSE_DETAIL_WISHLIST_UNKNOWN_ERROR)
            ->setCode(WishlistsRestApiConfig::RESPONSE_CODE_WISHLIST_UNKNOWN_ERROR)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        return $this->restResourceBuilder->createRestResponse()->addError($errorMessage);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCantAddWishlistItemErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(WishlistsRestApiConfig::RESPONSE_CODE_WISHLIST_CANT_ADD_ITEM)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setDetail(WishlistsRestApiConfig::RESPONSE_DETAIL_WISHLIST_CANT_ADD_ITEM);

        return $restResponse = $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createWishlistNotFoundErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(WishlistsRestApiConfig::RESPONSE_CODE_WISHLIST_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(WishlistsRestApiConfig::RESPONSE_DETAIL_WISHLIST_NOT_FOUND);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createItemSkuMissingErrorToResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(WishlistsRestApiConfig::RESPONSE_CODE_ID_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(WishlistsRestApiConfig::RESPONSE_DETAIL_ID_IS_NOT_SPECIFIED);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createWishlistAlreadyExistsErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setDetail(WishlistsRestApiConfig::RESPONSE_DETAIL_WISHLIST_WITH_SAME_NAME_ALREADY_EXISTS)
            ->setCode(WishlistsRestApiConfig::RESPONSE_CODE_WISHLIST_WITH_SAME_NAME_ALREADY_EXISTS)
            ->setStatus(Response::HTTP_BAD_REQUEST);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createWishlistNameInvalidErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setDetail(WishlistsRestApiConfig::RESPONSE_DETAIL_WISHLIST_NAME_INVALID)
            ->setCode(WishlistsRestApiConfig::RESPONSE_CODE_WISHLIST_NAME_INVALID)
            ->setStatus(Response::HTTP_BAD_REQUEST);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
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

        return (new RestErrorMessageTransfer())->fromArray($errorMappingData[$errorIdentifier]);
    }

    /**
     * @param string $errorIdentifier
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createDefaultUnexpectedRestErrorMessage(string $errorIdentifier): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setDetail($errorIdentifier);
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
