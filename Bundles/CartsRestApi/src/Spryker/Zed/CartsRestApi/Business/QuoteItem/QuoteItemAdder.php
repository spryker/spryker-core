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

class QuoteItemAdder implements QuoteItemAdderInterface
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
    public function add(RestCartItemRequestTransfer $restCartItemRequestTransfer): QuoteResponseTransfer
    {
        $restCartItemRequestTransfer
            ->requireCartItem()
            ->requireCustomerReference();

        $quoteResponseTransfer = $this->quoteItemMapper->createQuoteResponseTransfer($restCartItemRequestTransfer);
        if (!$restCartItemRequestTransfer->getCartUuid()) {
            $quoteResponseTransfer
                ->addError((new QuoteErrorTransfer())->setMessage(SharedCartsRestApiConfig::RESPONSE_CODE_CART_ID_MISSING));

            return $this->quoteItemMapper->mapQuoteResponseErrorsToRestCodes(
                $quoteResponseTransfer
            );
        }

        if ($restCartItemRequestTransfer->getCartItem()->getSku() || $restCartItemRequestTransfer->getCartItem()->getGroupKey()) {
            $quoteResponseTransfer
                ->addError((new QuoteErrorTransfer())->setMessage(SharedCartsRestApiConfig::RESPONSE_CODE_ITEM_VALIDATION));

            return $this->quoteItemMapper->mapQuoteResponseErrorsToRestCodes(
                $quoteResponseTransfer
            );
        }

        $quoteResponseTransfer = $this->cartReader->findQuoteByUuid(
            $this->quoteItemMapper->mapRestCartItemRequestTransferToQuoteTransfer($restCartItemRequestTransfer)
        );
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $persistentCartChangeTransfer = $this->quoteItemMapper->createPersistentCartChangeTransfer(
            $quoteResponseTransfer->getQuoteTransfer(),
            $restCartItemRequestTransfer
        );

        $quoteResponseTransfer = $this->persistentCartFacade->add($persistentCartChangeTransfer);
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            $quoteResponseTransfer
                ->addError((new QuoteErrorTransfer())->setMessage(SharedCartsRestApiConfig::RESPONSE_CODE_ITEM_NOT_FOUND));

            return $this->quoteItemMapper->mapQuoteResponseErrorsToRestCodes(
                $quoteResponseTransfer
            );
        }

        return $quoteResponseTransfer;
    }
}
