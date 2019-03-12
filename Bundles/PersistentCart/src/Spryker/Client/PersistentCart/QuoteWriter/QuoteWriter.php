<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart\QuoteWriter;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToQuoteClientInterface;
use Spryker\Client\PersistentCartExtension\Dependency\Plugin\QuotePersistPluginInterface;

class QuoteWriter implements QuoteWriterInterface
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
     * @var \Spryker\Client\PersistentCartExtension\Dependency\Plugin\QuotePersistPluginInterface
     */
    protected $quotePersistPlugin;

    /**
     * @param \Spryker\Client\PersistentCartExtension\Dependency\Plugin\QuotePersistPluginInterface $quotePersistPlugin
     * @param \Spryker\Client\PersistentCart\Dependency\Client\PersistentCartToQuoteClientInterface $quoteClient
     */
    public function __construct(
        QuotePersistPluginInterface $quotePersistPlugin,
        PersistentCartToQuoteClientInterface $quoteClient
    ) {
        $this->quotePersistPlugin = $quotePersistPlugin;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function persist(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        if ($this->quoteClient->getStorageStrategy() !== static::STORAGE_STRATEGY_DATABASE) {
            $this->quoteClient->setQuote($quoteTransfer);

            return (new QuoteResponseTransfer())
                ->setIsSuccessful(true)
                ->setQuoteTransfer($quoteTransfer);
        }

        return $this->quotePersistPlugin->persist($quoteTransfer);
    }
}
