<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Cart;

use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToCartFacadeInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface;

class QuoteUpdater implements QuoteUpdaterInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface
     */
    protected $persistentCartFacade;

    /**
     * @var \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\Cart\QuoteReaderInterface
     */
    protected $quoteReader;

    /**
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\CartsRestApi\Business\Cart\QuoteReaderInterface $quoteReader
     */
    public function __construct(
        CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade,
        CartsRestApiToCartFacadeInterface $cartFacade,
        QuoteReaderInterface $quoteReader
    ) {
        $this->persistentCartFacade = $persistentCartFacade;
        $this->cartFacade = $cartFacade;
        $this->quoteReader = $quoteReader;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuoteByUuid(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = $this->quoteReader->findQuoteByUuid($quoteTransfer);
        if (!$quoteResponseTransfer->getQuoteTransfer()) {
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
