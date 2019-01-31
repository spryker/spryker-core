<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\QuoteItem;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\RestCartItemRequestTransfer;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteCreatorInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\Mapper\QuoteItemMapperInterface;

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
     * @var \Spryker\Zed\CartsRestApi\Business\QuoteItem\Mapper\QuoteItemMapperInterface
     */
    protected $quoteItemMapper;

    /**
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface $cartReader
     * @param \Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemAdderInterface $quoteItemAdder
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteCreatorInterface $quoteCreator
     * @param \Spryker\Zed\CartsRestApi\Business\QuoteItem\Mapper\QuoteItemMapperInterface $quoteItemMapper
     */
    public function __construct(
        QuoteReaderInterface $cartReader,
        QuoteItemAdderInterface $quoteItemAdder,
        QuoteCreatorInterface $quoteCreator,
        QuoteItemMapperInterface $quoteItemMapper
    ) {
        $this->cartReader = $cartReader;
        $this->quoteItemAdder = $quoteItemAdder;
        $this->quoteCreator = $quoteCreator;
        $this->quoteItemMapper = $quoteItemMapper;
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
            $quoteResponseTransfer = $this->quoteCreator->createQuote(
                $this->quoteItemMapper->createRestQuoteRequestTransfer($restCartItemRequestTransfer)
            );

            return $this->addItemToCart($quoteResponseTransfer, $restCartItemRequestTransfer);
        }

        $quoteResponseTransfer = $this->cartReader->findQuoteByUuid(
            $this->quoteItemMapper->mapRestCartItemRequestTransferToQuoteTransfer($restCartItemRequestTransfer)
        );

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        return $this->addItemToCart($quoteResponseTransfer, $restCartItemRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     * @param \Generated\Shared\Transfer\RestCartItemRequestTransfer $restCartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function addItemToCart(
        QuoteResponseTransfer $quoteResponseTransfer,
        RestCartItemRequestTransfer $restCartItemRequestTransfer
    ) {
        return $this->quoteItemAdder
            ->add($restCartItemRequestTransfer->setCartUuid($quoteResponseTransfer->getQuoteTransfer()->getUuid()));
    }
}
