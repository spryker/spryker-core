<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\QuoteItem;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Shared\CartsRestApi\CartsRestApiConfig as CartsRestApiSharedConfig;
use Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface;
use Spryker\Zed\CartsRestApi\Business\QuoteItem\Mapper\QuoteItemMapperInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToCartFacadeInterface;

class QuoteItemAdder implements QuoteItemAdderInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface
     */
    protected $quoteReader;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\QuoteItem\Mapper\QuoteItemMapperInterface
     */
    protected $quoteItemMapper;

    /**
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\CartsRestApi\Business\QuoteItem\Mapper\QuoteItemMapperInterface $quoteItemMapper
     */
    public function __construct(
        CartsRestApiToCartFacadeInterface $cartFacade,
        QuoteReaderInterface $quoteReader,
        QuoteItemMapperInterface $quoteItemMapper
    ) {
        $this->cartFacade = $cartFacade;
        $this->quoteReader = $quoteReader;
        $this->quoteItemMapper = $quoteItemMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function add(RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer): QuoteResponseTransfer
    {
        $restCartItemsAttributesTransfer
            ->requireCustomerReference()
            ->requireSku()
            ->requireQuoteUuid();

        $quoteResponseTransfer = $this->quoteReader->findQuoteByUuid(
            $this->quoteItemMapper->mapRestCartItemsAttributesTransferToQuoteTransfer(
                $restCartItemsAttributesTransfer,
                new QuoteTransfer()
            )
        );

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $persistentCartChangeTransfer = $this->createCartChangeTransfer(
            $quoteResponseTransfer->getQuoteTransfer(),
            $restCartItemsAttributesTransfer
        );

        $quoteResponseTransfer = $this->cartFacade->addToQuote($persistentCartChangeTransfer);
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            $quoteResponseTransfer
                ->addError((new QuoteErrorTransfer())
                    ->setErrorIdentifier(CartsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_ADDING_CART_ITEM));
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransfer(
        QuoteTransfer $quoteTransfer,
        RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
    ): CartChangeTransfer {
        $quoteTransfer
            ->setCustomer((new CustomerTransfer())
                ->setCustomerReference($restCartItemsAttributesTransfer->getCustomerReference()));

        return (new CartChangeTransfer())
            ->setQuote($quoteTransfer)
            ->addItem((new ItemTransfer())
                ->setSku($restCartItemsAttributesTransfer->getSku())
                ->setQuantity($restCartItemsAttributesTransfer->getQuantity()));
    }
}
