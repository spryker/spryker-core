<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\QuoteItem;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartItemRequestTransfer;
use Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer;
use Generated\Shared\Transfer\RestQuoteRequestTransfer;
use Spryker\Shared\CartsRestApi\CartsRestApiConfig as CartsRestApiSharedConfig;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteCreatorInterface;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\Mapper\QuoteItemMapperInterface;
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
     * @var \Spryker\Zed\CartsRestApi\Business\QuoteItem\Mapper\QuoteItemMapperInterface
     */
    protected $quoteItemMapper;

    /**
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\CartsRestApi\Business\QuoteItem\QuoteItemAdderInterface $quoteItemAdder
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteCreatorInterface $quoteCreator
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\CartsRestApi\Business\QuoteItem\Mapper\QuoteItemMapperInterface $quoteItemMapper
     */
    public function __construct(
        QuoteReaderInterface $quoteReader,
        QuoteItemAdderInterface $quoteItemAdder,
        QuoteCreatorInterface $quoteCreator,
        CartsRestApiToStoreFacadeInterface $storeFacade,
        QuoteItemMapperInterface $quoteItemMapper
    ) {
        $this->quoteReader = $quoteReader;
        $this->quoteItemAdder = $quoteItemAdder;
        $this->quoteCreator = $quoteCreator;
        $this->storeFacade = $storeFacade;
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

        $restQuoteCollectionRequest = (new RestQuoteCollectionRequestTransfer())
            ->setCustomerReference($restCartItemRequestTransfer->getCustomerReference());

        $customerCarts = $this->quoteReader->getQuoteCollectionByCustomerAndStore($restQuoteCollectionRequest);
        if ($customerCarts->getQuoteCollection() && $customerCarts->getQuoteCollection()->getQuotes()->count()) {
            $restCartItemRequestTransfer->setCartUuid($customerCarts->getQuoteCollection()->getQuotes()[0]->getUuid());
        }

        if (!$restCartItemRequestTransfer->getCartUuid()) {
            return $this->createGuestQuote($restCartItemRequestTransfer);
        }

        $quoteResponseTransfer = $this->quoteReader->findQuoteByUuid(
            $this->quoteItemMapper->mapRestCartItemRequestTransferToQuoteTransfer($restCartItemRequestTransfer)
        );
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        if (!$restCartItemRequestTransfer->getCartItem()->getSku()) {
            $quoteResponseTransfer
                ->addError((new QuoteErrorTransfer())->setMessage(CartsRestApiSharedConfig::RESPONSE_CODE_ITEM_VALIDATION));

            return $this->quoteItemMapper->mapQuoteResponseErrorsToRestCodes(
                $quoteResponseTransfer
            );
        }

        return $this->addItem($quoteResponseTransfer, $restCartItemRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartItemRequestTransfer $restCartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function createGuestQuote(RestCartItemRequestTransfer $restCartItemRequestTransfer): QuoteResponseTransfer
    {
        $currentStore = $this->storeFacade->getCurrentStore();
        $quoteTransfer = (new QuoteTransfer())
            ->setStore($currentStore)
            ->setCurrency((new CurrencyTransfer())
                ->setCode($currentStore->getDefaultCurrencyIsoCode()));

        $restQuoteRequestTransfer = (new RestQuoteRequestTransfer())
            ->setCustomerReference($restCartItemRequestTransfer->getCustomerReference())
            ->setQuote($quoteTransfer);

        $quoteResponseTransfer = $this->quoteCreator->createQuote($restQuoteRequestTransfer);
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        return $this->addItem($quoteResponseTransfer, $restCartItemRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     * @param \Generated\Shared\Transfer\RestCartItemRequestTransfer $restCartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function addItem(
        QuoteResponseTransfer $quoteResponseTransfer,
        RestCartItemRequestTransfer $restCartItemRequestTransfer
    ): QuoteResponseTransfer {
        $quoteResponseTransfer = $this->quoteItemAdder
            ->add($restCartItemRequestTransfer->setCartUuid($quoteResponseTransfer->getQuoteTransfer()->getUuid()));
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->quoteItemMapper->mapQuoteResponseErrorsToRestCodes(
                $quoteResponseTransfer
            );
        }

        return $quoteResponseTransfer;
    }
}
