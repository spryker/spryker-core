<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Shared\Quote\QuoteConfig;

/**
 * @method \Spryker\Client\MultiCart\MultiCartFactory getFactory()
 */
class MultiCartClient extends AbstractClient implements MultiCartClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return null|\Generated\Shared\Transfer\QuoteTransfer
     */
    public function findActiveCart(): ?QuoteTransfer
    {
        foreach ($this->getQuoteCollection()->getQuotes() as $quoteTransfer) {
            if ($quoteTransfer->getIsActive()) {
                return $quoteTransfer;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollection(): QuoteCollectionTransfer
    {
        return $this->getFactory()->createMultiCartSession()->getQuoteCollection();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $quoteName
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function findQuoteByName($quoteName): ?QuoteTransfer
    {
        $quoteCollection = $this->getQuoteCollection();
        foreach ($quoteCollection->getQuotes() as $quoteTransfer) {
            if ($quoteTransfer->getName() === $quoteName) {
                return $quoteTransfer;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return bool
     */
    public function isMultiCartAllowed()
    {
        return $this->getFactory()->getQuoteClient()->getStorageStrategy() === QuoteConfig::STORAGE_STRATEGY_DATABASE;
    }

    /**
     * Specification:
     * - Get suffix for duplicated quote name
     *
     * @api
     *
     * @return string
     */
    public function getDuplicatedQuoteNameSuffix()
    {
        return $this->getFactory()->getBundleConfig()->getDuplicatedQuoteNameSuffix();
    }
}
