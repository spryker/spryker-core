<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\QuoteItem;

use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\RestCartItemRequestTransfer;
use Spryker\Shared\CartsRestApi\CartsRestApiConfig as SharedCartsRestApiConfig;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\Mapper\QuoteItemMapperInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface;

class QuoteItemUpdater implements QuoteItemUpdaterInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface
     */
    protected $persistentCartFacade;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface
     */
    protected $cartReader;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\QuoteItem\Mapper\QuoteItemMapperInterface
     */
    protected $quoteItemMapper;

    /**
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface $cartReader
     * @param \Spryker\Zed\CartsRestApi\Business\QuoteItem\Mapper\QuoteItemMapperInterface $quoteItemMapper
     */
    public function __construct(
        CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade,
        QuoteReaderInterface $cartReader,
        QuoteItemMapperInterface $quoteItemMapper
    ) {
        $this->persistentCartFacade = $persistentCartFacade;
        $this->cartReader = $cartReader;
        $this->quoteItemMapper = $quoteItemMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartItemRequestTransfer $restCartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function changeItemQuantity(RestCartItemRequestTransfer $restCartItemRequestTransfer): QuoteResponseTransfer
    {
        $restCartItemRequestTransfer
            ->requireCartUuid()
            ->requireCustomerReference()
            ->requireCartItem();

        $quoteResponseTransfer = $this->cartReader->findQuoteByUuid(
            $this->quoteItemMapper->mapRestCartItemRequestTransferToQuoteTransfer($restCartItemRequestTransfer)
        );

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $ifRequestedItemPresentInQuote = $this->checkIfRequestedItemPresentInQuote(
            $restCartItemRequestTransfer->getCartItem()->getSku(),
            $quoteResponseTransfer->getQuoteTransfer()->getItems()->getArrayCopy()
        );

        if (!$ifRequestedItemPresentInQuote) {
            $quoteResponseTransfer
                ->addError((new QuoteErrorTransfer())->setMessage(SharedCartsRestApiConfig::RESPONSE_CODE_ITEM_NOT_FOUND));

            return $this->quoteItemMapper->mapQuoteResponseErrorsToRestCodes(
                $quoteResponseTransfer
            );
        }

        $persistentCartChangeQuantityTransfer = $this->quoteItemMapper->createPersistentCartChangeQuantityTransfer(
            $quoteResponseTransfer->getQuoteTransfer(),
            $restCartItemRequestTransfer
        );

        if (!$restCartItemRequestTransfer->getCartItem()->getSku()) {
            $quoteResponseTransfer
                ->addError((new QuoteErrorTransfer())->setMessage(SharedCartsRestApiConfig::RESPONSE_CODE_MISSING_REQUIRED_PARAMETER));

            return $this->quoteItemMapper->mapQuoteResponseErrorsToRestCodes(
                $quoteResponseTransfer
            );
        }

        $quoteResponseTransfer = $this->persistentCartFacade->changeItemQuantity($persistentCartChangeQuantityTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->quoteItemMapper->mapQuoteResponseErrorsToRestCodes(
                $quoteResponseTransfer
            );
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param string $itemSku
     * @param array $items
     *
     * @return bool
     */
    protected function checkIfRequestedItemPresentInQuote(string $itemSku, array $items): bool
    {
        if (empty($items)) {
            return false;
        }

        foreach ($items as $item) {
            if ($item->getSku() === $itemSku) {
                return true;
            }
        }

        return false;
    }
}
