<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\StorageStrategy;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Quote\Session\QuoteSessionInterface;
use Spryker\Shared\Quote\QuoteConfig;

class SessionStorageStrategy implements StorageStrategyInterface
{
    /**
     * @var \Spryker\Client\Quote\Session\QuoteSessionInterface
     */
    protected $quoteSession;

    /**
     * @param \Spryker\Client\Quote\Session\QuoteSessionInterface $quoteSession
     */
    public function __construct(QuoteSessionInterface $quoteSession)
    {
        $this->quoteSession = $quoteSession;
    }

    /**
     * @return string
     */
    public function getStorageStrategy()
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
        return $this->quoteSession->getQuote();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function setQuote(QuoteTransfer $quoteTransfer)
    {
        $this->quoteSession->setQuote($quoteTransfer);
    }

    /**
     * @return void
     */
    public function clearQuote()
    {
        $this->quoteSession->clearQuote();
    }
}
