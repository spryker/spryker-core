<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\GuestCartItem;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToZedRequestClientInterface;
use Spryker\Glue\CartsRestApi\Processor\GuestCart\GuestCartCreatorInterface;
use Spryker\Glue\CartsRestApi\Processor\GuestCart\GuestCartReaderInterface;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class GuestCartItemAdder implements GuestCartItemAdderInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\GuestCart\GuestCartReaderInterface
     */
    protected $guestCartReader;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\GuestCart\GuestCartCreatorInterface
     */
    protected $guestCartCreator;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface
     */
    protected $cartItemsResourceMapper;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface
     */
    protected $guestCartRestResponseBuilder;

    /**
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface $cartClient
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface $quoteClient
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToZedRequestClientInterface $zedRequestClient
     * @param \Spryker\Glue\CartsRestApi\Processor\GuestCart\GuestCartReaderInterface $guestCartReader
     * @param \Spryker\Glue\CartsRestApi\Processor\GuestCart\GuestCartCreatorInterface $guestCartCreator
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface $cartItemsResourceMapper
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder
     */
    public function __construct(
        CartsRestApiToCartClientInterface $cartClient,
        CartsRestApiToQuoteClientInterface $quoteClient,
        CartsRestApiToZedRequestClientInterface $zedRequestClient,
        GuestCartReaderInterface $guestCartReader,
        GuestCartCreatorInterface $guestCartCreator,
        CartItemsResourceMapperInterface $cartItemsResourceMapper,
        GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder
    ) {
        $this->cartClient = $cartClient;
        $this->quoteClient = $quoteClient;
        $this->zedRequestClient = $zedRequestClient;
        $this->guestCartReader = $guestCartReader;
        $this->guestCartCreator = $guestCartCreator;
        $this->cartItemsResourceMapper = $cartItemsResourceMapper;
        $this->guestCartRestResponseBuilder = $guestCartRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addItem(
        RestRequestInterface $restRequest,
        RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
    ): RestResponseInterface {
        $parentResource = $restRequest->findParentResourceByType(CartsRestApiConfig::RESOURCE_GUEST_CARTS);
        if (!$parentResource) {
            $quoteTransfer = $this->guestCartReader->getCustomerQuote($restRequest)
                ?? $this->guestCartCreator->createQuoteTransfer($restRequest);

            return $this->addItemToQuote($quoteTransfer, $restCartItemsAttributesTransfer);
        }

        $quoteResponseTransfer = $this->guestCartReader->getQuoteTransferByUuid($parentResource->getId(), $restRequest);
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->guestCartRestResponseBuilder->createGuestCartNotFoundErrorRestResponse();
        }

        return $this->addItemToQuote($quoteResponseTransfer->getQuoteTransfer(), $restCartItemsAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addItemToQuote(QuoteTransfer $quoteTransfer, RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer): RestResponseInterface
    {
        $this->quoteClient->setQuote($quoteTransfer);
        $quoteTransfer = $this->cartClient->addItem(
            $this->cartItemsResourceMapper->mapItemAttributesToItemTransfer($restCartItemsAttributesTransfer)
        );

        $errors = $this->zedRequestClient->getLastResponseErrorMessages();
        if (count($errors) > 0) {
            return $this->guestCartRestResponseBuilder->createGuestCartErrorRestResponseFromErrorMessageTransfer($errors);
        }

        return $this->guestCartRestResponseBuilder->createGuestCartRestResponse($quoteTransfer);
    }
}
