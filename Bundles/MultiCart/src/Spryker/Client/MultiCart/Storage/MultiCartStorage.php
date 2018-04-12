<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\Storage;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\MultiCart\Dependency\Client\MultiCartToSessionClientInterface;

class MultiCartStorage implements MultiCartStorageInterface
{
    public const SESSION_KEY_QUOTE_COLLECTION = 'SESSION_KEY_QUOTE_COLLECTION';

    /**
     * @var \Spryker\Client\MultiCart\Dependency\Client\MultiCartToSessionClientInterface
     */
    protected $sessionClient;

    /**
     * @param \Spryker\Client\MultiCart\Dependency\Client\MultiCartToSessionClientInterface $sessionClient
     */
    public function __construct(MultiCartToSessionClientInterface $sessionClient)
    {
        $this->sessionClient = $sessionClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     *
     * @return void
     */
    public function setQuoteCollection(QuoteCollectionTransfer $quoteCollectionTransfer): void
    {
        $this->sessionClient->set(static::SESSION_KEY_QUOTE_COLLECTION, $quoteCollectionTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollection(): QuoteCollectionTransfer
    {
        return $this->sessionClient->get(static::SESSION_KEY_QUOTE_COLLECTION, new QuoteCollectionTransfer());
    }

    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function findQuoteById(int $idQuote): ?QuoteTransfer
    {
        $quoteCollection = $this->getQuoteCollection();
        foreach ($quoteCollection->getQuotes() as $quoteTransfer) {
            if ($quoteTransfer->getIdQuote() === $idQuote) {
                return $quoteTransfer;
            }
        }

        return null;
    }
}
