<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\CartItem;

use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Generated\Shared\Transfer\RestCartItemRequestTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Zed\CartsRestApi\Business\Cart\CartReaderInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface;

class CartItemUpdater implements CartItemUpdaterInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface
     */
    protected $persistentCartFacade;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\Cart\CartReaderInterface
     */
    protected $cartReader;

    /**
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade
     * @param \Spryker\Zed\CartsRestApi\Business\Cart\CartReaderInterface $cartReader
     */
    public function __construct(
        CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade,
        CartReaderInterface $cartReader
    ) {
        $this->persistentCartFacade = $persistentCartFacade;
        $this->cartReader = $cartReader;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartItemRequestTransfer $restCartItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function changeItemQuantity(RestCartItemRequestTransfer $restCartItemRequestTransfer): QuoteResponseTransfer
    {
        $restQuoteRequestTransfer
            ->requireQuote()
            ->requireCustomerReference()
            ->requireQuoteUuid();

        $quoteTransfer = $restQuoteRequestTransfer->getQuote();
        $quoteResponseTransfer = $this->cartReader->findQuoteByUuid($quoteTransfer);
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $originalQuoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        $quoteTransfer = $this->processQuoteData($quoteTransfer, $originalQuoteTransfer);

        $quoteResponseTransfer = $this->validateQuoteResponse(
            $originalQuoteTransfer,
            $quoteTransfer,
            $quoteResponseTransfer
        );

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $quoteTransfer = $this->cartFacade->reloadItems($originalQuoteTransfer
                ->fromArray($quoteTransfer->modifiedToArray()));
            $quoteUpdateRequestTransfer = (new QuoteUpdateRequestTransfer())
                ->fromArray($quoteTransfer->modifiedToArray(), true);
            $quoteUpdateRequestAttributesTransfer = (new QuoteUpdateRequestAttributesTransfer())
                ->fromArray($quoteTransfer->modifiedToArray(), true);
            $quoteUpdateRequestTransfer->setQuoteUpdateRequestAttributes($quoteUpdateRequestAttributesTransfer);
            $quoteResponseTransfer = $this->persistentCartFacade->updateQuote($quoteUpdateRequestTransfer);
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $originalQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function validateQuoteResponse(
        QuoteTransfer $originalQuoteTransfer,
        QuoteTransfer $quoteTransfer,
        QuoteResponseTransfer $quoteResponseTransfer
    ): QuoteResponseTransfer {
        if (count($originalQuoteTransfer->getItems()) > 0 && $quoteTransfer->getPriceMode()) {
            $quoteResponseTransfer
                ->setIsSuccessful(false)
                ->addError((new QuoteErrorTransfer())
                    ->setMessage(CartsRestApiConfig::RESPONSE_MESSAGE_PRICE_MODE_CANT_BE_CHANGED));
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $originalQuoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function processQuoteData(QuoteTransfer $quoteTransfer, QuoteTransfer $originalQuoteTransfer): QuoteTransfer
    {
        if (!$quoteTransfer->getCurrency()->getCode()) {
            $quoteTransfer->setCurrency($originalQuoteTransfer->getCurrency());
        }

        if (!$quoteTransfer->getStore()->getName()) {
            $quoteTransfer->setStore($originalQuoteTransfer->getStore());
        }

        return $quoteTransfer;
    }
}
