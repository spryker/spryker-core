<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\Session;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Quote\Dependency\Plugin\QuoteToCurrencyInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class QuoteSession implements QuoteSessionInterface
{

    const QUOTE_SESSION_IDENTIFIER = 'quote session identifier';

    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    /**
     * @var \Spryker\Client\Currency\Plugin\CurrencyPluginInterface
     */
    protected $currencyPlugin;

    /**
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @param \Spryker\Client\Quote\Dependency\Plugin\QuoteToCurrencyInterface|null $currencyPlugin
     */
    public function __construct(
        SessionInterface $session,
        QuoteToCurrencyInterface $currencyPlugin = null
    ) {
        $this->session = $session;
        $this->currencyPlugin = $currencyPlugin;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote()
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer = $this->session->get(static::QUOTE_SESSION_IDENTIFIER, $quoteTransfer);
        $this->setCurrency($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function setQuote(QuoteTransfer $quoteTransfer)
    {
        $this->setCurrency($quoteTransfer);
        $this->session->set(static::QUOTE_SESSION_IDENTIFIER, $quoteTransfer);
    }

    /**
     * @return $this
     */
    public function clearQuote()
    {
        $this->setQuote(new QuoteTransfer());

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function setCurrency(QuoteTransfer $quoteTransfer)
    {
        if (!$this->currencyPlugin) {
            return;
        }

        $quoteTransfer->setCurrency($this->currencyPlugin->getCurrent());
    }

}
