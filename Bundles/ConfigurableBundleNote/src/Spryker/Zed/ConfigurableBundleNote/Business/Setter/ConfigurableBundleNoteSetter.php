<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleNote\Business\Setter;

use Generated\Shared\Transfer\ConfiguredBundleNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Zed\ConfigurableBundleNote\Dependency\Facade\ConfigurableBundleNoteToQuoteFacadeInterface;

class ConfigurableBundleNoteSetter implements ConfigurableBundleNoteSetterInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundleNote\Dependency\Facade\ConfigurableBundleNoteToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\ConfigurableBundleNote\Dependency\Facade\ConfigurableBundleNoteToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(ConfigurableBundleNoteToQuoteFacadeInterface $quoteFacade)
    {
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleNoteRequestTransfer $configuredBundleNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setConfiguredBundleNote(
        ConfiguredBundleNoteRequestTransfer $configuredBundleNoteRequestTransfer
    ): QuoteResponseTransfer {
        $quoteResponseTransfer = $this->quoteFacade
            ->findQuoteById(
                $configuredBundleNoteRequestTransfer->getQuote()->getIdQuote()
            );

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $quoteResponseTransfer = $this->updateConfiguredBundlesWithNotes(
            $configuredBundleNoteRequestTransfer,
            $quoteResponseTransfer
        );

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        $quoteTransfer->setCustomer($configuredBundleNoteRequestTransfer->getQuote()->getCustomer());

        return $this->quoteFacade->updateQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleNoteRequestTransfer $configuredBundleNoteRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function updateConfiguredBundlesWithNotes(
        ConfiguredBundleNoteRequestTransfer $configuredBundleNoteRequestTransfer,
        QuoteResponseTransfer $quoteResponseTransfer
    ): QuoteResponseTransfer {
        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
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

        return $quoteResponseTransfer
            ->setQuoteTransfer($quoteTransfer)
            ->setIsSuccessful($isSuccessful);
    }
}
