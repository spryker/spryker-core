<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestWishlistsAttributesTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistsResourceMapperInterface;
use Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class WishlistsRestResponseBuilder implements WishlistsRestResponseBuilderInterface
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
     * @var \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistsResourceMapperInterface
     */
    protected $wishlistsResourceMapper;

    /**
     * @param \Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig $wishlistsRestApiConfig
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistsResourceMapperInterface $wishlistsResourceMapper
     */
    public function __construct(WishlistsRestApiConfig $wishlistsRestApiConfig, RestResourceBuilderInterface $restResourceBuilder, WishlistsResourceMapperInterface $wishlistsResourceMapper)
    {
        $this->wishlistsRestApiConfig = $wishlistsRestApiConfig;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->wishlistsResourceMapper = $wishlistsResourceMapper;
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function createWishlistNotFoundError(): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(WishlistsRestApiConfig::RESPONSE_CODE_WISHLIST_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(WishlistsRestApiConfig::RESPONSE_DETAIL_WISHLIST_NOT_FOUND);
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

        $restSharedCartsAttributesTransfer = $this->wishlistsResourceMapper
            ->mapWishlistTransferToRestWishlistsAttributes($wishlistTransfer);

        return $restResponse->addResource(
            $this->createRestWishlistResource(
                $wishlistTransfer->getUuid(),
                $restSharedCartsAttributesTransfer
            )
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
    public function createErrorResponseFromZedErrors(array $errorIdentifiers): RestResponseInterface
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
     * @param string $wishlistUuid
     * @param \Generated\Shared\Transfer\RestWishlistsAttributesTransfer $restWishlistsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createRestWishlistResource(string $wishlistUuid, RestWishlistsAttributesTransfer $restWishlistsAttributesTransfer): RestResourceInterface
    {
        return $this->restResourceBuilder->createRestResource(
            WishlistsRestApiConfig::RESOURCE_WISHLISTS,
            $wishlistUuid,
            $restWishlistsAttributesTransfer
        );
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
}
