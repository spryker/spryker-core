<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Quote\QuoteFactory getFactory()
 */
class QuoteClient extends AbstractClient implements QuoteClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote()
    {
        return $this->getFactory()->getStorageStrategy()->getQuote();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function setQuote(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->getStorageStrategy()->setQuote($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function clearQuote()
    {
        $this->getFactory()->getStorageStrategy()->clearQuote();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getStorageStrategy()
    {
        return $this->getFactory()->getStorageStrategy()->getStorageStrategy();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteLocked(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()->getStorageStrategy()->isQuoteLocked($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteEditable(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()->getStorageStrategy()->isQuoteEditable($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function lockQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()->getStorageStrategy()->lockQuote($quoteTransfer);
    }
}
