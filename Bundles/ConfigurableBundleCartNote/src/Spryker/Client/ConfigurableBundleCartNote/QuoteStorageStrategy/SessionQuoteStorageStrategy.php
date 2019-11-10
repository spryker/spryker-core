<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCartNote\QuoteStorageStrategy;

use Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ConfigurableBundleCartNote\Dependency\Client\ConfigurableBundleCartNoteToQuoteClientInterface;

class SessionQuoteStorageStrategy implements QuoteStorageStrategyInterface
{
    protected const STORAGE_STRATEGY = 'session';

    /**
     * @var \Spryker\Client\ConfigurableBundleCartNote\Dependency\Client\ConfigurableBundleCartNoteToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \Spryker\Client\ConfigurableBundleCartNote\Dependency\Client\ConfigurableBundleCartNoteToQuoteClientInterface $quoteClient
     */
    public function __construct(ConfigurableBundleCartNoteToQuoteClientInterface $quoteClient)
    {
        $this->quoteClient = $quoteClient;
    }

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
        $quoteResponseTransfer = $this->storeCartNoteToConfigurableBundle($configuredBundleCartNoteRequestTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $this->quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function storeCartNoteToConfigurableBundle(
        ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
    ): QuoteResponseTransfer {
        $quoteTransfer = $this->quoteClient->getQuote();
        $itemCollectionTransfer = $this->getItemCollectionTransferByConfigurableBundleGroupKey(
            $quoteTransfer,
            $configuredBundleCartNoteRequestTransfer->getConfigurableBundleGroupKey()
        );

        if ($itemCollectionTransfer->getItems()->count() === 0) {
            return (new QuoteResponseTransfer())
                ->setIsSuccessful(false);
        }

        foreach ($itemCollectionTransfer->getItems() as $itemTransfer) {
            $itemTransfer
                ->getConfiguredBundle()
                ->setCartNote($configuredBundleCartNoteRequestTransfer->getCartNote());
        }

        return (new QuoteResponseTransfer())
            ->setQuoteTransfer($quoteTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $configurableBundleGroupKey
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    protected function getItemCollectionTransferByConfigurableBundleGroupKey(
        QuoteTransfer $quoteTransfer,
        string $configurableBundleGroupKey
    ): ItemCollectionTransfer {
        $itemCollectionTransfer = new ItemCollectionTransfer();

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getConfiguredBundle()->getGroupKey() === $configurableBundleGroupKey) {
                $itemCollectionTransfer->addItem($itemTransfer);
            }
        }

        return $itemCollectionTransfer;
    }
}
