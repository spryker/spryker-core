<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartNote\Business\Model;

use Generated\Shared\Transfer\QuoteCartNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteItemCartNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartNote\Dependency\Facade\CartNoteToQuoteFacadeInterface;
use Spryker\Zed\CartNoteExtension\Dependency\Plugin\QuoteItemFinderPluginInterface;

class QuoteCartNoteSetter implements QuoteCartNoteSetterInterface
{
    /**
     * @var \Spryker\Zed\CartNote\Dependency\Facade\CartNoteToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\CartNoteExtension\Dependency\Plugin\QuoteItemFinderPluginInterface
     */
    protected $quoteItemFinderPlugin;

    /**
     * @param \Spryker\Zed\CartNote\Dependency\Facade\CartNoteToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\CartNoteExtension\Dependency\Plugin\QuoteItemFinderPluginInterface $quoteItemFinderPlugin
     */
    public function __construct(CartNoteToQuoteFacadeInterface $quoteFacade, QuoteItemFinderPluginInterface $quoteItemFinderPlugin)
    {
        $this->quoteFacade = $quoteFacade;
        $this->quoteItemFinderPlugin = $quoteItemFinderPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCartNoteRequestTransfer $quoteCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setQuoteNote(QuoteCartNoteRequestTransfer $quoteCartNoteRequestTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = $this->quoteFacade->findQuoteById($quoteCartNoteRequestTransfer->getIdQuote());
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }
        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        $quoteTransfer->setCustomer($quoteCartNoteRequestTransfer->getCustomer())
            ->setCartNote($quoteCartNoteRequestTransfer->getCartNote());

        return $this->quoteFacade->updateQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteItemCartNoteRequestTransfer $quoteItemCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setQuoteItemNote(QuoteItemCartNoteRequestTransfer $quoteItemCartNoteRequestTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = $this->quoteFacade->findQuoteById($quoteItemCartNoteRequestTransfer->getIdQuote());
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }
        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        $quoteTransfer->setCustomer($quoteItemCartNoteRequestTransfer->getCustomer());
        $itemTransferCollection = $this->findQuoteItems(
            $quoteTransfer,
            $quoteItemCartNoteRequestTransfer->getSku(),
            $quoteItemCartNoteRequestTransfer->getGroupKey()
        );
        if (!count($itemTransferCollection)) {
            $quoteResponseTransfer = new QuoteResponseTransfer();
            $quoteResponseTransfer->setIsSuccessful(false);

            return $quoteResponseTransfer;
        }
        $this->updateItemsCartNote($itemTransferCollection, $quoteItemCartNoteRequestTransfer->getCartNote());

        return $this->quoteFacade->updateQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransferCollection
     * @param string $note
     *
     * @return void
     */
    protected function updateItemsCartNote(array $itemTransferCollection, string $note): void
    {
        foreach ($itemTransferCollection as $itemTransfer) {
            $itemTransfer->setCartNote($note);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function findQuoteItems(QuoteTransfer $quoteTransfer, $sku, $groupKey = null): array
    {
        return $this->quoteItemFinderPlugin->findItem($quoteTransfer, $sku, $groupKey);
    }
}
