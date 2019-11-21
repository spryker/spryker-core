<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCartNote\Business\Setter;

use Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
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
    public function setCartNoteToConfiguredBundle(
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
        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
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

        return $quoteResponseTransfer
            ->setQuoteTransfer($quoteTransfer)
            ->setIsSuccessful($isSuccessful);
    }
}
