<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Communication\Plugin\CartReorder;

use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartPostReorderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PersistentCart\Business\PersistentCartFacadeInterface getFacade()
 * @method \Spryker\Zed\PersistentCart\PersistentCartConfig getConfig()
 * @method \Spryker\Zed\PersistentCart\Communication\PersistentCartCommunicationFactory getFactory()
 */
class UpdateQuoteCartPostReorderPlugin extends AbstractPlugin implements CartPostReorderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `CartReorderTransfer.quote.idQuote` to be set.
     * - Updates quote with provided `CartReorderTransfer.quote`.
     * - Returns `CartReorderTransfer` with updated `QuoteTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function postReorder(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer
    {
        $quoteTransfer = $cartReorderTransfer->getQuoteOrFail();
        if (!$quoteTransfer->getIdQuote()) {
            return $cartReorderTransfer;
        }

        $quoteTransfer = $this->getFacade()
            ->updateQuote($this->createQuoteUpdateRequestTransfer($quoteTransfer))
            ->getQuoteTransfer();

        return $cartReorderTransfer->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteUpdateRequestTransfer
     */
    protected function createQuoteUpdateRequestTransfer(QuoteTransfer $quoteTransfer): QuoteUpdateRequestTransfer
    {
        return (new QuoteUpdateRequestTransfer())
            ->fromArray($quoteTransfer->toArray(), true)
            ->setQuoteUpdateRequestAttributes((new QuoteUpdateRequestAttributesTransfer())->fromArray($quoteTransfer->toArray(), true));
    }
}
