<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCartNote\QuoteStorageStrategy;

use Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

class SessionQuoteStorageStrategy implements QuoteStorageStrategyInterface
{
    protected const STORAGE_STRATEGY = 'session';

    /**
     * @return string
     */
    public function getStorageStrategy(): string
    {
        return static::STORAGE_STRATEGY;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setCartNoteToConfiguredBundle(
        ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
    ): QuoteResponseTransfer {
        $quoteTransfer = $configuredBundleCartNoteRequestTransfer->getQuote();
        $isSuccessful = false;

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getConfiguredBundle()) {
                continue;
            }

            if ($itemTransfer->getConfiguredBundle()->getGroupKey() !== $configuredBundleCartNoteRequestTransfer->getGroupKey()) {
                continue;
            }

            $itemTransfer->getConfiguredBundle()->setCartNote($configuredBundleCartNoteRequestTransfer->getCartNote());
            $isSuccessful = true;
        }

        return (new QuoteResponseTransfer())
            ->setQuoteTransfer($quoteTransfer)
            ->setIsSuccessful($isSuccessful);
    }
}
