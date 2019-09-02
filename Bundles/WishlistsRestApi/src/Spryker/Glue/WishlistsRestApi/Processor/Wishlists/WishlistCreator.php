<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\Wishlists;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestWishlistsAttributesTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistMapperInterface;
use Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface;
use Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class WishlistCreator implements WishlistCreatorInterface
{
    /**
     * @uses \Spryker\Zed\Wishlist\Business\Model\Writer::ERROR_MESSAGE_NAME_ALREADY_EXISTS
     */
    protected const ERROR_MESSAGE_NAME_ALREADY_EXISTS = 'wishlist.validation.error.name.already_exists';

    /**
     * @uses \Spryker\Zed\Wishlist\Business\Model\Writer::ERROR_MESSAGE_NAME_HAS_INCORRECT_FORMAT
     */
    protected const ERROR_MESSAGE_NAME_HAS_INCORRECT_FORMAT = 'wishlist.validation.error.name.wrong_format';

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface
     */
    protected $wishlistClient;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistMapperInterface
     */
    protected $wishlistMapper;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface
     */
    protected $wishlistRestResponseBuilder;

    /**
     * @param \Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface $wishlistClient
     * @param \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistMapperInterface $wishlistMapper
     * @param \Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface $wishlistRestResponseBuilder
     */
    public function __construct(
        WishlistsRestApiToWishlistClientInterface $wishlistClient,
        WishlistMapperInterface $wishlistMapper,
        WishlistRestResponseBuilderInterface $wishlistRestResponseBuilder
    ) {
        $this->wishlistClient = $wishlistClient;
        $this->wishlistMapper = $wishlistMapper;
        $this->wishlistRestResponseBuilder = $wishlistRestResponseBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\RestWishlistsAttributesTransfer $attributesTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function create(RestWishlistsAttributesTransfer $attributesTransfer, RestRequestInterface $restRequest): RestResponseInterface
    {
        $wishlistTransfer = $this->wishlistMapper->mapWishlistAttributesToWishlistTransfer(new WishlistTransfer(), $attributesTransfer);
        $wishlistTransfer->setFkCustomer((int)$restRequest->getRestUser()->getSurrogateIdentifier());

        $wishlistResponseTransfer = $this->wishlistClient->validateAndCreateWishlist($wishlistTransfer);
        if (!$wishlistResponseTransfer->getIsSuccess()) {
            return $this->getRestResponseByErrors($wishlistResponseTransfer->getErrors());
        }

        return $this->wishlistRestResponseBuilder
            ->createWishlistsRestResponse($wishlistResponseTransfer->getWishlist());
    }

    /**
     * @param array $errors
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getRestResponseByErrors(array $errors): RestResponseInterface
    {
        foreach ($errors as $error) {
            return $this->getRestErrorResponse($error);
        }

        return $this->getRestErrorResponse();
    }

    /**
     * @param string|null $error
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getRestErrorResponse(?string $error = null): RestResponseInterface
    {
        if ($error === static::ERROR_MESSAGE_NAME_ALREADY_EXISTS) {
            return $this->wishlistRestResponseBuilder
                ->createErrorResponseFromErrorMessage(
                    (new RestErrorMessageTransfer())
                    ->setDetail(WishlistsRestApiConfig::RESPONSE_DETAIL_WISHLIST_WITH_SAME_NAME_ALREADY_EXISTS)
                    ->setCode(WishlistsRestApiConfig::RESPONSE_CODE_WISHLIST_WITH_SAME_NAME_ALREADY_EXISTS)
                    ->setStatus(Response::HTTP_BAD_REQUEST)
                );
        }

        if ($error === static::ERROR_MESSAGE_NAME_HAS_INCORRECT_FORMAT) {
            return $this->wishlistRestResponseBuilder
                ->createErrorResponseFromErrorMessage(
                    (new RestErrorMessageTransfer())
                        ->setDetail(WishlistsRestApiConfig::RESPONSE_DETAIL_WISHLIST_NAME_INVALID)
                        ->setCode(WishlistsRestApiConfig::RESPONSE_CODE_WISHLIST_NAME_INVALID)
                        ->setStatus(Response::HTTP_BAD_REQUEST)
                );
        }

        return $this->wishlistRestResponseBuilder
            ->createErrorResponseFromErrorMessage(
                (new RestErrorMessageTransfer())
                    ->setDetail(WishlistsRestApiConfig::RESPONSE_DETAIL_WISHLIST_UNKNOWN_ERROR)
                    ->setCode(WishlistsRestApiConfig::RESPONSE_CODE_WISHLIST_UNKNOWN_ERROR)
                    ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            );
    }
}
