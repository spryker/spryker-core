<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\GuestCartItem;

use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToCartClientInterface;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToQuoteClientInterface;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface;
use Spryker\Glue\CartsRestApi\Processor\GuestCart\GuestCartCreatorInterface;
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
     * @var \Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface
     */
    protected $cartReader;

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
     * @param \Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface $cartReader
     * @param \Spryker\Glue\CartsRestApi\Processor\GuestCart\GuestCartCreatorInterface $guestCartCreator
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemsResourceMapperInterface $cartItemsResourceMapper
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder
     */
    public function __construct(
        CartsRestApiToCartClientInterface $cartClient,
        CartsRestApiToQuoteClientInterface $quoteClient,
        CartReaderInterface $cartReader,
        GuestCartCreatorInterface $guestCartCreator,
        CartItemsResourceMapperInterface $cartItemsResourceMapper,
        GuestCartRestResponseBuilderInterface $guestCartRestResponseBuilder
    ) {
        $this->cartClient = $cartClient;
        $this->quoteClient = $quoteClient;
        $this->cartReader = $cartReader;
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
        $quoteResponseTransfers = $this->cartReader->getCustomerQuotes();
        $quotes = $quoteResponseTransfers->getQuotes();
        $quoteTransfer = $quotes[0] ?? $this->guestCartCreator->create($restRequest);
        $this->quoteClient->setQuote($quoteTransfer);
        $quoteTransfer = $this->cartClient->addItem(
            $this->cartItemsResourceMapper->mapItemAttributesToItemTransfer($restCartItemsAttributesTransfer)
        );

        return $this->guestCartRestResponseBuilder->createGuestCartRestResponse($quoteTransfer);
    }
}
