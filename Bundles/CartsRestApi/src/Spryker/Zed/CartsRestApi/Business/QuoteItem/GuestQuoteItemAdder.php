<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\QuoteItem;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteCreatorInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeInterface;

class GuestQuoteItemAdder implements GuestQuoteItemAdderInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface
     */
    protected $quoteReader;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemAdderInterface
     */
    protected $quoteItemAdder;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\Quote\QuoteCreatorInterface
     */
    protected $quoteCreator;

    /**
     * @var \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemAdderInterface $quoteItemAdder
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteCreatorInterface $quoteCreator
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(
        QuoteReaderInterface $quoteReader,
        QuoteItemAdderInterface $quoteItemAdder,
        QuoteCreatorInterface $quoteCreator,
        CartsRestApiToStoreFacadeInterface $storeFacade,
        CartsRestApiToQuoteFacadeInterface $quoteFacade
    ) {
        $this->quoteReader = $quoteReader;
        $this->quoteItemAdder = $quoteItemAdder;
        $this->quoteCreator = $quoteCreator;
        $this->storeFacade = $storeFacade;
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @deprecated Use addToGuestCart() instead.
     *
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addItemToGuestCart(RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer): QuoteResponseTransfer
    {
        $restCartItemsAttributesTransfer
            ->requireSku()
            ->requireCustomerReference();

        $cartItemRequestTransfer = (new CartItemRequestTransfer())
            ->fromArray($restCartItemsAttributesTransfer->toArray(), true);

        return $this->addToGuestCart($cartItemRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addToGuestCart(CartItemRequestTransfer $cartItemRequestTransfer): QuoteResponseTransfer
    {
        $cartItemRequestTransfer
            ->requireSku()
            ->requireCustomer();

        $cartItemRequestTransfer->getCustomer()->requireCustomerReference();

        $guestQuoteCollection = $this->quoteReader->getQuoteCollection(
            (new QuoteCriteriaFilterTransfer())
                ->setCustomerReference($cartItemRequestTransfer->getCustomer()->getCustomerReference())
        );

        $customerQuotes = $guestQuoteCollection->getQuotes();

        if (!$customerQuotes->count()) {
            return $this->createGuestQuote($cartItemRequestTransfer);
        }

        $cartItemRequestTransfer->setQuoteUuid($customerQuotes[0]->getUuid());

        return $this->addItem($cartItemRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthResponseTransfer $oauthResponseTransfer
     *
     * @return void
     */
    public function addGuestQuoteItemsToCustomerQuote(OauthResponseTransfer $oauthResponseTransfer): void
    {
        $oauthResponseTransfer
            ->requireCustomerReference();

        if (!$oauthResponseTransfer->getAnonymousCustomerReference()) {
            return;
        }

        $anonymousCustomerReference = $oauthResponseTransfer->getAnonymousCustomerReference();
        $guestQuoteCollection = $this->quoteReader->getQuoteCollection(
            (new QuoteCriteriaFilterTransfer())->setCustomerReference($anonymousCustomerReference)
        );

        $guestQuotes = $guestQuoteCollection->getQuotes();
        if (!$guestQuotes->count()) {
            return;
        }

        $questQuote = $guestQuotes[0];

        if (!$questQuote->getItems()->count()) {
            return;
        }

        $customerReference = $oauthResponseTransfer->getCustomerReference();
        $customerQuoteCollection = $this->quoteReader->getQuoteCollection(
            (new QuoteCriteriaFilterTransfer())->setCustomerReference($customerReference)
        );

        $customerQuotes = $customerQuoteCollection->getQuotes();
        if (!$customerQuotes->count()) {
            return;
        }

        $customerQuote = $customerQuotes[0];

        foreach ($questQuote->getItems() as $item) {
            $cartItemRequestTransfer = (new CartItemRequestTransfer())
                ->setQuoteUuid($customerQuote->getUuid())
                ->setCustomer((new CustomerTransfer())->setCustomerReference($customerReference))
                ->setSku($item->getSku())
                ->setQuantity($item->getQuantity());

            $this->addItem($cartItemRequestTransfer);
        }

        $questQuote->setCustomer((new CustomerTransfer())->setCustomerReference($anonymousCustomerReference));
        $this->quoteFacade->deleteQuote($questQuote);
    }

    /**
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function createGuestQuote(CartItemRequestTransfer $cartItemRequestTransfer): QuoteResponseTransfer
    {
        $quoteTransfer = $this->createQuoteTransfer();

        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference($cartItemRequestTransfer->getCustomer()->getCustomerReference());

        $quoteTransfer->setCustomer($customerTransfer);

        $quoteResponseTransfer = $this->quoteCreator->createQuote($quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $cartItemRequestTransfer->setQuoteUuid($quoteResponseTransfer->getQuoteTransfer()->getUuid());

        return $this->addItem($cartItemRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function addItem(CartItemRequestTransfer $cartItemRequestTransfer): QuoteResponseTransfer
    {
        return $this->quoteItemAdder->addToCart($cartItemRequestTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(): QuoteTransfer
    {
        $currentStore = $this->storeFacade->getCurrentStore();

        return (new QuoteTransfer())
            ->setStore($currentStore)
            ->setCurrency((new CurrencyTransfer())
                ->setCode($currentStore->getDefaultCurrencyIsoCode()));
    }
}
