<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\QuoteItem;

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
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addItemToGuestCart(RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer): QuoteResponseTransfer
    {
        $restCartItemsAttributesTransfer
            ->requireSku()
            ->requireCustomerReference();

        if (!$restCartItemsAttributesTransfer->getQuoteUuid()) {
            return $this->createGuestQuote($restCartItemsAttributesTransfer);
        }

        $customerQuoteCollection = $this->quoteReader->getQuoteCollection(
            (new QuoteCriteriaFilterTransfer())->setCustomerReference($restCartItemsAttributesTransfer->getCustomerReference())
        );

        $customerQuotes = $customerQuoteCollection->getQuotes();
        file_put_contents('vcv11.txt', print_r($customerQuotes, 1));
        if ($customerQuotes->count()) {
            $restCartItemsAttributesTransfer->setQuoteUuid($customerQuotes[0]->getUuid());
        }

        $quoteResponseTransfer = $this->quoteReader->findQuoteByUuid(
            $this->quoteItemMapper->mapRestCartItemsAttributesTransferToQuoteTransfer(
                $restCartItemsAttributesTransfer,
                new QuoteTransfer()
            )
        );
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $restCartItemsAttributesTransfer->setQuoteUuid($quoteResponseTransfer->getQuoteTransfer()->getUuid());

        return $this->addItem($restCartItemsAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function createGuestQuote(RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer): QuoteResponseTransfer
    {
        $quoteTransfer = $this->createQuoteTransfer();
        $quoteTransfer->setCustomer((new CustomerTransfer())->setCustomerReference($restCartItemsAttributesTransfer->getCustomerReference()));

        $quoteResponseTransfer = $this->quoteCreator->createQuote($quoteTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $restCartItemsAttributesTransfer->setQuoteUuid($quoteResponseTransfer->getQuoteTransfer()->getUuid());

        return $this->addItem($restCartItemsAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function addItem(RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = $this->quoteItemAdder
            ->add($restCartItemsAttributesTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            $quoteResponseTransfer
                ->addError((new QuoteErrorTransfer())
                    ->setErrorIdentifier(CartsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_ADDING_CART_ITEM));
        }

        return $quoteResponseTransfer;
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
