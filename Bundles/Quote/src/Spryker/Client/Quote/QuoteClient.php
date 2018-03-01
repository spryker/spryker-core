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
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     * TODO: get from session all the time
     */
    public function getQuote()
    {
        return $this->getFactory()->createSession()->getQuote();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     * TODO:
     * - session strategy: set quote in session
     * - persistence strategy: set quote in session, throw exception when something is changed in quote which is stored in database
     */
    public function setQuote(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createSession()->setQuote($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     * TODO:
     * - session strategy: clear it from session
     * - persistent strategy: we do a zed request to clear the quote, then we clear it from session as well
     */
    public function clearQuote()
    {
        $this->getFactory()->createSession()->clearQuote();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     * TODO: not needed
     */
    public function syncQuote()
    {
        $this->getFactory()->createSession()->syncQuote();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     * TODO: not needed
     */
    public function pushQuote()
    {
        $this->getFactory()->createSession()->pushQuote();
    }

    /**
     * @return string
     */
    public function getStorageStrategy()
    {

    }
}
