<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleNote\QuoteStorageStrategy;

use Generated\Shared\Transfer\ConfiguredBundleNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

class SessionQuoteStorageStrategy implements QuoteStorageStrategyInterface
{
    /**
     * @uses \Spryker\Shared\Quote\QuoteConfig::STORAGE_STRATEGY_SESSION
     */
    protected const STORAGE_STRATEGY = 'session';

    /**
     * @return string
     */
    public function getStorageStrategy(): string
    {
        return static::STORAGE_STRATEGY;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleNoteRequestTransfer $configuredBundleNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setConfiguredBundleNote(
        ConfiguredBundleNoteRequestTransfer $configuredBundleNoteRequestTransfer
    ): QuoteResponseTransfer {
        $quoteTransfer = $configuredBundleNoteRequestTransfer->getQuote();
        $isSuccessful = false;
        $configuredBundleTransfer = $configuredBundleNoteRequestTransfer->getConfiguredBundle();

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getConfiguredBundle()) {
                continue;
            }

            if ($itemTransfer->getConfiguredBundle()->getGroupKey() !== $configuredBundleTransfer->getGroupKey()) {
                continue;
            }

            $itemTransfer->getConfiguredBundle()->setNote($configuredBundleTransfer->getNote());
            $isSuccessful = true;
        }

        return (new QuoteResponseTransfer())
            ->setQuoteTransfer($quoteTransfer)
            ->setIsSuccessful($isSuccessful);
    }
}
