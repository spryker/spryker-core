<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCartNote\Business\Setter;

use Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ConfigurableBundleCartNote\Dependency\Facade\ConfigurableBundleCartNoteToQuoteFacadeInterface;

class ConfigurableBundleCartNoteSetter implements ConfigurableBundleCartNoteSetterInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundleCartNote\Dependency\Facade\ConfigurableBundleCartNoteToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\ConfigurableBundleCartNote\Dependency\Facade\ConfigurableBundleCartNoteToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(ConfigurableBundleCartNoteToQuoteFacadeInterface $quoteFacade)
    {
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setCartNoteToConfigurableBundle(
        ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
    ): QuoteResponseTransfer {
        $quoteResponseTransfer = $this->quoteFacade
            ->findQuoteById(
                $configuredBundleCartNoteRequestTransfer->getQuote()->getIdQuote()
            );

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $quoteResponseTransfer = $this->updateConfiguredBundlesWithCartNotes(
            $configuredBundleCartNoteRequestTransfer,
            $quoteResponseTransfer
        );

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        $quoteTransfer->setCustomer($configuredBundleCartNoteRequestTransfer->getQuote()->getCustomer());

        return $this->quoteFacade->updateQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function updateConfiguredBundlesWithCartNotes(
        ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer,
        QuoteResponseTransfer $quoteResponseTransfer
    ): QuoteResponseTransfer {
        $itemCollectionTransfer = $this->getItemCollectionByConfigurableBundleGroupKey(
            $quoteResponseTransfer->getQuoteTransfer(),
            $configuredBundleCartNoteRequestTransfer->getGroupKey()
        );

        if ($itemCollectionTransfer->getItems()->count() === 0) {
            return $quoteResponseTransfer
                ->setIsSuccessful(false);
        }

        foreach ($itemCollectionTransfer->getItems() as $itemTransfer) {
            $itemTransfer
                ->getConfiguredBundle()
                ->setCartNote($configuredBundleCartNoteRequestTransfer->getCartNote());
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $configurableBundleGroupKey
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    protected function getItemCollectionByConfigurableBundleGroupKey(
        QuoteTransfer $quoteTransfer,
        string $configurableBundleGroupKey
    ): ItemCollectionTransfer {
        $itemCollectionTransfer = new ItemCollectionTransfer();

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $configuredBundleTransfer = $itemTransfer->getConfiguredBundle();
            if ($configuredBundleTransfer && $configuredBundleTransfer->getGroupKey() === $configurableBundleGroupKey) {
                $itemCollectionTransfer->addItem($itemTransfer);
            }
        }

        return $itemCollectionTransfer;
    }
}
