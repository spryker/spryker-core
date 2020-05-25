<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\QuoteItem;

use ArrayObject;
use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Shared\CartsRestApi\CartsRestApiConfig as CartsRestApiSharedConfig;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteCreatorInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface;
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
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemAdderInterface $quoteItemAdder
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteCreatorInterface $quoteCreator
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        QuoteReaderInterface $quoteReader,
        QuoteItemAdderInterface $quoteItemAdder,
        QuoteCreatorInterface $quoteCreator,
        CartsRestApiToStoreFacadeInterface $storeFacade
    ) {
        $this->quoteReader = $quoteReader;
        $this->quoteItemAdder = $quoteItemAdder;
        $this->quoteCreator = $quoteCreator;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @deprecated Use {@link addToGuestCart()} instead.
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

        $quoteCollectionTransfer = $this->quoteReader->getQuoteCollection(
            (new QuoteCriteriaFilterTransfer())
                ->setCustomerReference($cartItemRequestTransfer->getCustomer()->getCustomerReference())
        );

        $customerQuoteTransfers = $quoteCollectionTransfer->getQuotes();

        if ($cartItemRequestTransfer->getQuoteUuid() && !$customerQuoteTransfers->count()) {
            return $this->createCartNotFoundError();
        }

        if (!$customerQuoteTransfers->count()) {
            return $this->createGuestQuote($cartItemRequestTransfer);
        }

        $customerQuoteTransfer = $this->findQuoteInQuoteCollection(
            $customerQuoteTransfers,
            $cartItemRequestTransfer->getQuoteUuid()
        );
        if (!$customerQuoteTransfer) {
            return $this->createCartNotFoundError();
        }

        $cartItemRequestTransfer->setQuoteUuid($customerQuoteTransfer->getUuid());

        return $this->addItem($cartItemRequestTransfer);
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

    /**
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function createCartNotFoundError(): QuoteResponseTransfer
    {
        return (new QuoteResponseTransfer())
            ->setIsSuccessful(false)
            ->addError(
                (new QuoteErrorTransfer())
                    ->setErrorIdentifier(CartsRestApiSharedConfig::ERROR_IDENTIFIER_CART_NOT_FOUND)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer[]|\ArrayObject $customerQuoteTransfers
     * @param string|null $quoteUuid
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    protected function findQuoteInQuoteCollection(
        ArrayObject $customerQuoteTransfers,
        ?string $quoteUuid
    ): ?QuoteTransfer {
        if (!$quoteUuid && $customerQuoteTransfers->offsetExists(0)) {
            return $customerQuoteTransfers->offsetGet(0);
        }

        foreach ($customerQuoteTransfers as $customerQuoteTransfer) {
            if ($customerQuoteTransfer->getUuid() === $quoteUuid) {
                return $customerQuoteTransfer;
            }
        }

        return null;
    }
}
