<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\Session;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Quote\Dependency\Client\QuoteToCurrencyClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class QuoteSession implements QuoteSessionInterface
{
    public const QUOTE_SESSION_IDENTIFIER = 'quote session identifier';

    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    /**
     * @var \Spryker\Client\Quote\Dependency\Plugin\QuoteTransferExpanderPluginInterface[]
     */
    protected $quoteTransferExpanderPlugins;

    /**
     * @var \Spryker\Client\Quote\Dependency\Client\QuoteToCurrencyClientInterface
     */
    protected $currencyClient;

    /**
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @param \Spryker\Client\Quote\Dependency\Client\QuoteToCurrencyClientInterface $currencyClient
     * @param \Spryker\Client\Quote\Dependency\Plugin\QuoteTransferExpanderPluginInterface[] $quoteTransferExpanderPlugins
     */
    public function __construct(
        SessionInterface $session,
        QuoteToCurrencyClientInterface $currencyClient,
        array $quoteTransferExpanderPlugins = []
    ) {
        $this->session = $session;
        $this->quoteTransferExpanderPlugins = $quoteTransferExpanderPlugins;
        $this->currencyClient = $currencyClient;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote()
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer = $this->session->get(static::QUOTE_SESSION_IDENTIFIER, $quoteTransfer);
        $this->setCurrency($quoteTransfer);

        $quoteTransfer = $this->expandQuoteTransfer($quoteTransfer);

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

        $quoteTransfer = $this->expandQuoteTransfer($quoteTransfer);

        $this->session->set(static::QUOTE_SESSION_IDENTIFIER, $quoteTransfer);
        $this->updateCurrency($quoteTransfer);
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
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function expandQuoteTransfer(QuoteTransfer $quoteTransfer)
    {
        foreach ($this->quoteTransferExpanderPlugins as $quoteTransferExpanderPlugin) {
            $quoteTransfer = $quoteTransferExpanderPlugin->expandQuote($quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function setCurrency(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getCurrency()) {
            return;
        }

        $quoteTransfer->setCurrency($this->currencyClient->getCurrent());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function updateCurrency(QuoteTransfer $quoteTransfer): void
    {
        $currencyTransfer = $this->currencyClient->getCurrent();
        if ($quoteTransfer->getCurrency()->getCode() !== $currencyTransfer->getCode()) {
            $this->currencyClient->setCurrentCurrencyIsoCode($quoteTransfer->getCurrency()->getCode());
        }
    }
}
