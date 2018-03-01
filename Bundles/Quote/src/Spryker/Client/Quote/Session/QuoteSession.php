<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\Session;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Quote\Dependency\Plugin\QuoteToCurrencyInterface;
use Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface;
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
     * @var \Spryker\Client\Quote\Dependency\Plugin\QuoteTransferExpanderPluginInterface[]
     */
    protected $quoteTransferExpanderPlugins;

    /**
     * @var \Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface
     */
    protected $storageStrategy;

    /**
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @param \Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface $storageStrategy
     * @param \Spryker\Client\Quote\Dependency\Plugin\QuoteToCurrencyInterface|null $currencyPlugin
     * @param \Spryker\Client\Quote\Dependency\Plugin\QuoteTransferExpanderPluginInterface[] $quoteTransferExpanderPlugins
     */
    public function __construct(
        SessionInterface $session,
        StorageStrategyInterface $storageStrategy, // TODO: Storage shouldn't be part of the session strategy, it should be the other way around.
        QuoteToCurrencyInterface $currencyPlugin = null,
        array $quoteTransferExpanderPlugins = []
    ) {
        $this->session = $session;
        $this->currencyPlugin = $currencyPlugin;
        $this->quoteTransferExpanderPlugins = $quoteTransferExpanderPlugins;
        $this->storageStrategy = $storageStrategy;
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
    }

    /**
     * @return $this
     */
    public function clearQuote()
    {
        $this->storageStrategy->clearQuote($this->getQuote());
        $this->syncQuote();

        return $this;
    }

    /**
     * @return void
     */
    public function syncQuote()
    {
        $quoteTransfer = $this->getQuote();
        $quoteTransfer->fromArray($this->storageStrategy->getQuote()->modifiedToArray(), true);
        $this->session->set(static::QUOTE_SESSION_IDENTIFIER, $quoteTransfer);
    }

    /**
     * @return void
     */
    public function pushQuote()
    {
        $quoteTransfer = $this->getQuote();
        $this->storageStrategy->saveQuote($quoteTransfer);
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
        if (!$this->currencyPlugin) {
            return;
        }

        $quoteTransfer->setCurrency($this->currencyPlugin->getCurrent());
    }
}
