<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\Session;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Quote\Dependency\Client\QuoteToCurrencyClientInterface;
use Spryker\Client\Quote\Dependency\Client\QuoteToStoreClientInterface;
use Spryker\Client\Session\SessionClientInterface;

class QuoteSession implements QuoteSessionInterface
{
    /**
     * @var string
     */
    public const QUOTE_SESSION_IDENTIFIER = 'quote session identifier';

    /**
     * @var \Spryker\Client\Session\SessionClientInterface
     */
    protected $session;

    /**
     * @var array<\Spryker\Client\QuoteExtension\Dependency\Plugin\QuoteTransferExpanderPluginInterface>
     */
    protected $quoteTransferExpanderPlugins;

    /**
     * @var \Spryker\Client\Quote\Dependency\Client\QuoteToCurrencyClientInterface
     */
    protected $currencyClient;

    /**
     * @var \Generated\Shared\Transfer\CurrencyTransfer|null
     */
    protected static $currencyTransfer;

    /**
     * @var \Spryker\Client\Quote\Dependency\Client\QuoteToStoreClientInterface
     */
    protected QuoteToStoreClientInterface $storeClient;

    /**
     * @param \Spryker\Client\Session\SessionClientInterface $session
     * @param \Spryker\Client\Quote\Dependency\Client\QuoteToCurrencyClientInterface $currencyClient
     * @param \Spryker\Client\Quote\Dependency\Client\QuoteToStoreClientInterface $storeClient
     * @param array<\Spryker\Client\QuoteExtension\Dependency\Plugin\QuoteTransferExpanderPluginInterface> $quoteTransferExpanderPlugins
     */
    public function __construct(
        SessionClientInterface $session,
        QuoteToCurrencyClientInterface $currencyClient,
        QuoteToStoreClientInterface $storeClient,
        array $quoteTransferExpanderPlugins = []
    ) {
        $this->session = $session;
        $this->quoteTransferExpanderPlugins = $quoteTransferExpanderPlugins;
        $this->currencyClient = $currencyClient;
        $this->storeClient = $storeClient;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote()
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer = $this->session->get($this->getQuoteSessionIdentifier(), $quoteTransfer);
        $this->setCurrency($quoteTransfer);

        $quoteTransfer = $this->expandQuoteTransfer($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return $this
     */
    public function setQuote(QuoteTransfer $quoteTransfer)
    {
        $this->setCurrency($quoteTransfer);

        $quoteTransfer = $this->expandQuoteTransfer($quoteTransfer);

        $this->session->set($this->getQuoteSessionIdentifier(), $quoteTransfer);
        $this->updateCurrency($quoteTransfer);

        return $this;
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

        $quoteTransfer->setCurrency($this->getCurrencyTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyTransfer(): CurrencyTransfer
    {
        if (!static::$currencyTransfer) {
            static::$currencyTransfer = $this->currencyClient->getCurrent();
        }

        return static::$currencyTransfer;
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

    /**
     * @return string
     */
    protected function getQuoteSessionIdentifier(): string
    {
        return sprintf(
            '%s %s',
            $this->storeClient->getCurrentStore()->getNameOrFail(),
            static::QUOTE_SESSION_IDENTIFIER,
        );
    }
}
