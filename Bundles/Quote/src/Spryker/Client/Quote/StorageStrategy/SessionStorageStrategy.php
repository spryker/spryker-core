<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\StorageStrategy;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Quote\Session\QuoteSession;
use Spryker\Shared\Quote\QuoteConfig;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionStorageStrategy implements StorageStrategyInterface
{
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
     * @return string
     */
    public function getStorageType()
    {
        return QuoteConfig::STORAGE_STRATEGY_SESSION;
    }

    /**
     * @return bool
     */
    public function isAllowed()
    {
        return true;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote()
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer = $this->session->get(QuoteSession::QUOTE_SESSION_IDENTIFIER, $quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveQuote(QuoteTransfer $quoteTransfer)
    {
        $this->session->set(QuoteSession::QUOTE_SESSION_IDENTIFIER, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return $this
     */
    public function clearQuote(QuoteTransfer $quoteTransfer)
    {
        $this->saveQuote(new QuoteTransfer());

        return $this;
    }
}
