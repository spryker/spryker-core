<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\QuoteItem;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartItemRequestTransfer;
use Generated\Shared\Transfer\RestQuoteRequestTransfer;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteCreatorInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface;

class GuestQuoteItemAdder implements GuestQuoteItemAdderInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface
     */
    protected $cartReader;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemAdderInterface
     */
    protected $quoteItemAdder;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\Quote\QuoteCreatorInterface
     */
    protected $quoteCreator;

    /**
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface $cartReader
     * @param \Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemAdderInterface $quoteItemAdder
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteCreatorInterface $quoteCreator
     */
    public function __construct(
        QuoteReaderInterface $cartReader,
        QuoteItemAdderInterface $quoteItemAdder,
        QuoteCreatorInterface $quoteCreator
    ) {
        $this->cartReader = $cartReader;
        $this->quoteItemAdder = $quoteItemAdder;
        $this->quoteCreator = $quoteCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartItemRequestTransfer $restCartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addItemToGuestCart(RestCartItemRequestTransfer $restCartItemRequestTransfer): QuoteResponseTransfer
    {
        $restCartItemRequestTransfer
            ->requireCartItem()
            ->requireCustomerReference();

        if (!$restCartItemRequestTransfer->getCartUuid()) {
            return $this->quoteCreator->createQuote(
                (new RestQuoteRequestTransfer())
                    ->setQuote((new QuoteTransfer())->addItem($restCartItemRequestTransfer->getCartItem()))
                    ->setCustomerReference($restCartItemRequestTransfer->getCustomerReference())
            );
        }

        $quoteResponseTransfer = $this->cartReader->findQuoteByUuid(
            (new QuoteTransfer())->setUuid($restCartItemRequestTransfer->getCartUuid())
        );
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        return $this->quoteItemAdder->add($restCartItemRequestTransfer);
    }
}
