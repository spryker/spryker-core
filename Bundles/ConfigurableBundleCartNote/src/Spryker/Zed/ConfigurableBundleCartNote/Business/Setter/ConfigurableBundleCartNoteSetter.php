<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCartNote\Business\Setter;

use Generated\Shared\Transfer\ConfigurableBundleCartNoteRequestTransfer;
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
     * @param \Generated\Shared\Transfer\ConfigurableBundleCartNoteRequestTransfer $configurableBundleCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setCartNoteToConfigurableBundle(
        ConfigurableBundleCartNoteRequestTransfer $configurableBundleCartNoteRequestTransfer
    ): QuoteResponseTransfer {
        $quoteResponseTransfer = $this->quoteFacade->findQuoteById($configurableBundleCartNoteRequestTransfer->getIdQuote());
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        $quoteTransfer->setCustomer($configurableBundleCartNoteRequestTransfer->getCustomer());
        $itemCollectionTransfer = $this->getItemCollectionByConfigurableBundleGroupKey(
            $quoteTransfer,
            $configurableBundleCartNoteRequestTransfer->getConfigurableBundleGroupKey()
        );

        if ($itemCollectionTransfer->getItems()->count() === 0) {
            return (new QuoteResponseTransfer())
                ->setIsSuccessful(false);
        }

        foreach ($itemCollectionTransfer->getItems() as $itemTransfer) {
            $itemTransfer
                ->getConfiguredBundle()
                ->setCartNote($configurableBundleCartNoteRequestTransfer->getCartNote());
        }

        return $this->quoteFacade->updateQuote($quoteTransfer);
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
            if ($itemTransfer->getConfiguredBundle()->getGroupKey() === $configurableBundleGroupKey) {
                $itemCollectionTransfer->addItem($itemTransfer);
            }
        }

        return $itemCollectionTransfer;
    }
}
