<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\CustomerCartReplacer;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToQuoteClientInterface;
use Spryker\Client\PersistentCartExtension\Dependency\Plugin\QuoteReplacePluginInterface;

class CustomerCartReplacer implements CustomerCartReplacerInterface
{
    /**
     * @uses \Spryker\Shared\Quote\QuoteConfig::STORAGE_STRATEGY_DATABASE
     */
    protected const STORAGE_STRATEGY_DATABASE = 'database';

    /**
     * @var \Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Client\PersistentCartExtension\Dependency\Plugin\QuoteReplacePluginInterface
     */
    protected $quoteReplacePlugin;

    /**
     * @param \Spryker\Client\PersistentCartExtension\Dependency\Plugin\QuoteReplacePluginInterface $quoteReplacePlugin
     * @param \Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToQuoteClientInterface $quoteClient
     */
    public function __construct(QuoteReplacePluginInterface $quoteReplacePlugin, PersistentCartToQuoteClientInterface $quoteClient)
    {
        $this->quoteReplacePlugin = $quoteReplacePlugin;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function replace(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        if ($this->quoteClient->getStorageStrategy() === static::STORAGE_STRATEGY_DATABASE) {
            $currentQuoteTransfer = $this->quoteClient->getQuote();

            $quoteTransfer->setIdQuote($currentQuoteTransfer->getIdQuote())
                ->setCustomer($currentQuoteTransfer->getCustomer())
                ->setCustomerReference($currentQuoteTransfer->getCustomerReference())
                ->setStore($currentQuoteTransfer->getStore());

            $quoteResponseTransfer = $this->quoteReplacePlugin->replace($quoteTransfer);

            if (!$quoteResponseTransfer->getIsSuccessful()) {
                return $quoteResponseTransfer;
            }

            $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        }

        $this->quoteClient->setQuote($quoteTransfer);

        return (new QuoteResponseTransfer())
            ->setIsSuccessful(true)
            ->setQuoteTransfer($quoteTransfer);
    }
}
