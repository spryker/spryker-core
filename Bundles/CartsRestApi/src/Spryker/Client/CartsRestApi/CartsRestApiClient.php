<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartsRestApi;

use Generated\Shared\Transfer\AssignGuestQuoteRequestTransfer;
use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CartsRestApi\CartsRestApiFactory getFactory()
 */
class CartsRestApiClient extends AbstractClient implements CartsRestApiClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteByUuid(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartsRestApiZedStub()
            ->findQuoteByUuid($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollection(QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): QuoteCollectionTransfer
    {
        return $this->getFactory()
            ->createCartsRestApiZedStub()
            ->getQuoteCollection($quoteCriteriaFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartsRestApiZedStub()
            ->updateQuote($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartsRestApiZedStub()
            ->createQuote($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartsRestApiZedStub()
            ->deleteQuote($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use updateItemQuantity() instead.
     *
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateItem(RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartsRestApiZedStub()
            ->updateItem($restCartItemsAttributesTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateItemQuantity(CartItemRequestTransfer $cartItemRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartsRestApiZedStub()
            ->updateItemQuantity($cartItemRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use addToCart() instead.
     *
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addItem(RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartsRestApiZedStub()
            ->addItem($restCartItemsAttributesTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addToCart(CartItemRequestTransfer $cartItemRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartsRestApiZedStub()
            ->addToCart($cartItemRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use removeItem() instead.
     *
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteItem(RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartsRestApiZedStub()
            ->deleteItem($restCartItemsAttributesTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function removeItem(CartItemRequestTransfer $cartItemRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartsRestApiZedStub()
            ->removeItem($cartItemRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use addToGuestCart() instead.
     *
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addItemToGuestCart(RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartsRestApiZedStub()
            ->addItemToGuestCart($restCartItemsAttributesTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addToGuestCart(CartItemRequestTransfer $cartItemRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartsRestApiZedStub()
            ->addToGuestCart($cartItemRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssignGuestQuoteRequestTransfer $assignGuestQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function assignGuestCartToRegisteredCustomer(AssignGuestQuoteRequestTransfer $assignGuestQuoteRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartsRestApiZedStub()
            ->assignGuestCartToRegisteredCustomer($assignGuestQuoteRequestTransfer);
    }
}
