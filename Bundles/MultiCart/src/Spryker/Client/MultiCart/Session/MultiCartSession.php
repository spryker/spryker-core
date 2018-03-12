<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\Session;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class MultiCartSession implements MultiCartSessionInterface
{
    const SESSION_KEY_QUOTE_COLLECTION = 'SESSION_KEY_QUOTE_COLLECTION';

    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    /**
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     */
    public function __construct(
        SessionInterface $session
    ) {
        $this->session = $session;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     *
     * @return void
     */
    public function setQuoteCollection(QuoteCollectionTransfer $quoteCollectionTransfer)
    {
        $this->session->set(static::SESSION_KEY_QUOTE_COLLECTION, $quoteCollectionTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollection(): QuoteCollectionTransfer
    {
        $quoteCollectionTransfer = new QuoteCollectionTransfer();
        $quoteCollectionTransfer = $this->session->get(static::SESSION_KEY_QUOTE_COLLECTION, $quoteCollectionTransfer);

        return $quoteCollectionTransfer;
    }
}
