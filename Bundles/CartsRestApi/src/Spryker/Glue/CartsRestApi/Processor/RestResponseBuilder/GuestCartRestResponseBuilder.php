<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class GuestCartRestResponseBuilder implements GuestCartRestResponseBuilderInterface
{
    protected const PATTERN_GUEST_CART_ITEM_RESOURCE_SELF_LINK = '%s/%s/%s/%s';
    protected const KEY_REST_RESOURCE_SELF_LINK = 'self';

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface
     */
    protected $cartsResourceMapper;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface
     */
    protected $cartItemsResourceMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface $cartsResourceMapper
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface $cartItemsResourceMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CartsResourceMapperInterface $cartsResourceMapper,
        CartItemsResourceMapperInterface $cartItemsResourceMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->cartsResourceMapper = $cartsResourceMapper;
        $this->cartItemsResourceMapper = $cartItemsResourceMapper;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createEmptyGuestCartRestResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createGuestCartRestResponse(QuoteTransfer $quoteTransfer): RestResponseInterface
    {
        $cartResource = $this->restResourceBuilder->createRestResource(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS,
            $quoteTransfer->getUuid(),
            $this->cartsResourceMapper->mapQuoteTransferToRestCartsAttributesTransfer($quoteTransfer)
        );

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $cartResource->addRelationship($this->createGuestCartItemResource($itemTransfer, $cartResource->getId()));
        }

        return $this->createEmptyGuestCartRestResponse()->addResource($cartResource);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createGuestCartNotFoundErrorRestResponse(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CartsRestApiConfig::RESPONSE_CODE_CART_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(CartsRestApiConfig::EXCEPTION_MESSAGE_CART_WITH_ID_NOT_FOUND);

        return $this->createEmptyGuestCartRestResponse()->addError($restErrorTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer[] $errors
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createGuestCartErrorRestResponseFromErrorMessageTransfer(array $errors): RestResponseInterface
    {
        $restResponse = $this->createEmptyGuestCartRestResponse();

        foreach ($errors as $messageTransfer) {
            $restErrorMessageTransfer = (new RestErrorMessageTransfer())
                ->setCode(CartsRestApiConfig::RESPONSE_CODE_ITEM_VALIDATION)
                ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->setDetail($messageTransfer->getValue());

            $restResponse->addError($restErrorMessageTransfer);
        }

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createGuestCartIdMissingError(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CartsRestApiConfig::RESPONSE_CODE_CART_ID_MISSING)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CartsRestApiConfig::EXCEPTION_MESSAGE_CART_ID_MISSING);

        return $this->createEmptyGuestCartRestResponse()->addError($restErrorTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $cartResourceId
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createGuestCartItemResource(ItemTransfer $itemTransfer, string $cartResourceId): RestResourceInterface
    {
        $itemResource = $this->restResourceBuilder->createRestResource(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            $itemTransfer->getGroupKey(),
            $this->cartItemsResourceMapper->mapCartItemAttributes($itemTransfer)
        );
        $itemResource->addLink(
            static::KEY_REST_RESOURCE_SELF_LINK,
            sprintf(
                static::PATTERN_GUEST_CART_ITEM_RESOURCE_SELF_LINK,
                CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                $cartResourceId,
                CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                $itemTransfer->getGroupKey()
            )
        );

        return $itemResource;
    }
}
