<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\Wishlists;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestWishlistsAttributesTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemsResourceMapperInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistsResourceMapperInterface;
use Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class WishlistsWriter implements WishlistsWriterInterface
{
    protected const WISHLIST_VALIDATION_ERROR_NAME_ALREADY_EXIST = 'wishlist.validation.error.name.already_exists';
    protected const WISHLIST_VALIDATION_ERROR_NAME_WRONG_FORMAT = 'wishlist.validation.error.name.wrong_format';

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface
     */
    protected $wishlistClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistsResourceMapperInterface
     */
    protected $wishlistsResourceMapper;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemsResourceMapperInterface
     */
    protected $wishlistsItemResourceMapper;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistsReaderInterface
     */
    protected $wishlistsReader;

    /**
     * @param \Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface $wishlistClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistsResourceMapperInterface $wishlistsResourceMapper
     * @param \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemsResourceMapperInterface $wishlistsItemResourceMapper
     * @param \Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistsReaderInterface $wishlistsReader
     */
    public function __construct(
        WishlistsRestApiToWishlistClientInterface $wishlistClient,
        RestResourceBuilderInterface $restResourceBuilder,
        WishlistsResourceMapperInterface $wishlistsResourceMapper,
        WishlistItemsResourceMapperInterface $wishlistsItemResourceMapper,
        WishlistsReaderInterface $wishlistsReader
    ) {
        $this->wishlistClient = $wishlistClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->wishlistsResourceMapper = $wishlistsResourceMapper;
        $this->wishlistsItemResourceMapper = $wishlistsItemResourceMapper;
        $this->wishlistsReader = $wishlistsReader;
    }

    /**
     * @param \Generated\Shared\Transfer\RestWishlistsAttributesTransfer $attributesTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function create(RestWishlistsAttributesTransfer $attributesTransfer, RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $wishlistTransfer = $this->wishlistsResourceMapper->mapWishlistAttributesToWishlistTransfer(new WishlistTransfer(), $attributesTransfer);
        $wishlistTransfer->setFkCustomer((int)$restRequest->getUser()->getSurrogateIdentifier());

        $wishlistResponseTransfer = $this->wishlistClient->validateAndCreateWishlist($wishlistTransfer);
        if (!$wishlistResponseTransfer->getIsSuccess() || $wishlistResponseTransfer->getWishlist() === null) {
            return $this->handleWishlistResponseTransferError(
                $wishlistResponseTransfer,
                WishlistsRestApiConfig::RESPONSE_CODE_WISHLIST_CANT_CREATE_WISHLIST,
                $restResponse
            );
        }

        $wishlistTransfer = $wishlistResponseTransfer->getWishlist();

        $wishlistResource = $this->restResourceBuilder->createRestResource(
            WishlistsRestApiConfig::RESOURCE_WISHLISTS,
            $wishlistTransfer->getUuid(),
            $this->wishlistsResourceMapper->mapWishlistTransferToRestWishlistsAttributes($wishlistTransfer)
        );

        return $restResponse->addResource($wishlistResource);
    }

    /**
     * @param \Generated\Shared\Transfer\RestWishlistsAttributesTransfer $attributesTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function update(RestWishlistsAttributesTransfer $attributesTransfer, RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        if (!$restRequest->getResource()->getId()) {
            return $this->createWishlistNotFoundError($restResponse);
        }

        $wishlistTransfer = $this->wishlistsReader->findWishlistByUuid($restRequest->getResource()->getId());
        if ($wishlistTransfer === null) {
            return $this->createWishlistNotFoundError($restResponse);
        }
        $wishlistTransfer = $this->wishlistsResourceMapper->mapWishlistAttributesToWishlistTransfer($wishlistTransfer, $attributesTransfer);

        $wishlistResponseTransfer = $this->wishlistClient->validateAndUpdateWishlist($wishlistTransfer);
        if (!$wishlistResponseTransfer->getIsSuccess()) {
            return $this->handleWishlistResponseTransferError(
                $wishlistResponseTransfer,
                WishlistsRestApiConfig::RESPONSE_CODE_WISHLIST_CANT_UPDATE_WISHLIST,
                $restResponse
            );
        }

        $wishlistOverviewTransfer = $this->wishlistsReader->findWishlistOverviewByUuid($wishlistResponseTransfer->getWishlist()->getUuid());
        $wishlistResource = $this->restResourceBuilder->createRestResource(
            WishlistsRestApiConfig::RESOURCE_WISHLISTS,
            $wishlistTransfer->getUuid(),
            $this->wishlistsResourceMapper->mapWishlistTransferToRestWishlistsAttributes($wishlistOverviewTransfer->getWishlist())
        );

        return $restResponse->addResource($wishlistResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function delete(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        if (!$restRequest->getResource()->getId()) {
            return $this->createWishlistNotFoundError($restResponse);
        }

        $wishlistUuid = $restRequest->getResource()->getId();
        $wishlistTransfer = $this->wishlistsReader->findWishlistByUuid($wishlistUuid);

        if ($wishlistTransfer === null) {
            return $this->createWishlistNotFoundError($restResponse);
        }

        $this->wishlistClient->removeWishlist($wishlistTransfer);

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistResponseTransfer $wishlistResponseTransfer
     * @param string $errorCode
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function handleWishlistResponseTransferError(WishlistResponseTransfer $wishlistResponseTransfer, string $errorCode, RestResponseInterface $restResponse): RestResponseInterface
    {
        foreach ($wishlistResponseTransfer->getErrors() as $error) {
            if ($error === static::WISHLIST_VALIDATION_ERROR_NAME_ALREADY_EXIST) {
                $restErrorMessageTransfer = (new RestErrorMessageTransfer())
                    ->setCode(WishlistsRestApiConfig::RESPONSE_CODE_WISHLIST_WITH_SAME_NAME_ALREADY_EXISTS)
                    ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setDetail(WishlistsRestApiConfig::RESPONSE_DETAIL_WISHLIST_WITH_SAME_NAME_ALREADY_EXISTS);

                return $restResponse->addError($restErrorMessageTransfer);
            }
            if ($error === static::WISHLIST_VALIDATION_ERROR_NAME_WRONG_FORMAT) {
                $restErrorMessageTransfer = (new RestErrorMessageTransfer())
                    ->setCode(WishlistsRestApiConfig::RESPONSE_CODE_WISHLIST_NAME_INVALID)
                    ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setDetail(WishlistsRestApiConfig::RESPONSE_DETAIL_WISHLIST_NAME_INVALID);

                return $restResponse->addError($restErrorMessageTransfer);
            }

            $restErrorMessageTransfer = (new RestErrorMessageTransfer())
                ->setCode($errorCode)
                ->setStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->setDetail($error);
            $restResponse->addError($restErrorMessageTransfer);
        }

        return $restResponse;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $response
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createWishlistNotFoundError(RestResponseInterface $response): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(WishlistsRestApiConfig::RESPONSE_CODE_WISHLIST_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(WishlistsRestApiConfig::RESPONSE_DETAIL_WISHLIST_NOT_FOUND);

        return $response->addError($restErrorMessageTransfer);
    }
}
