<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCartNote\QuoteStorageStrategy;

use Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer;
use Generated\Shared\Transfer\ItemCollectionTransfer;
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
    public function setCartNoteToConfigurableBundle(
        ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
    ): QuoteResponseTransfer {
        $itemCollectionTransfer = $this->getItemCollectionTransferByConfigurableBundleGroupKey($configuredBundleCartNoteRequestTransfer);

        if ($itemCollectionTransfer->getItems()->count() === 0) {
            return (new QuoteResponseTransfer())
                ->setQuoteTransfer($configuredBundleCartNoteRequestTransfer->getQuote())
                ->setIsSuccessful(false);
        }

        return $this->updateConfiguredBundlesWithCartNotes($itemCollectionTransfer, $configuredBundleCartNoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemCollectionTransfer $itemCollectionTransfer
     * @param \Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function updateConfiguredBundlesWithCartNotes(
        ItemCollectionTransfer $itemCollectionTransfer,
        ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
    ): QuoteResponseTransfer {
        foreach ($itemCollectionTransfer->getItems() as $itemTransfer) {
            $itemTransfer
                ->getConfiguredBundle()
                ->setCartNote($configuredBundleCartNoteRequestTransfer->getCartNote());
        }

        return (new QuoteResponseTransfer())
            ->setQuoteTransfer($configuredBundleCartNoteRequestTransfer->getQuote())
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    protected function getItemCollectionTransferByConfigurableBundleGroupKey(
        ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
    ): ItemCollectionTransfer {
        $itemCollectionTransfer = new ItemCollectionTransfer();

        foreach ($configuredBundleCartNoteRequestTransfer->getQuote()->getItems() as $itemTransfer) {
            if ($itemTransfer->getConfiguredBundle()->getGroupKey() === $configuredBundleCartNoteRequestTransfer->getGroupKey()) {
                $itemCollectionTransfer->addItem($itemTransfer);
            }
        }

        return $itemCollectionTransfer;
    }
}
