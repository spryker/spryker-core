<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Communication\Controller;

use Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteSyncRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\PersistentCart\Business\PersistentCartFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addItemAction(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->add($persistentCartChangeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addValidItemsAction(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->addValid($persistentCartChangeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function removeItemAction(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->remove($persistentCartChangeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function reloadItemsAction(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->reloadItems($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function changeItemQuantityAction(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->changeItemQuantity($persistentCartChangeQuantityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function decreaseItemQuantityAction(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->decreaseItemQuantity($persistentCartChangeQuantityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function increaseItemQuantityAction(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->increaseItemQuantity($persistentCartChangeQuantityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteSyncRequestTransfer $quoteSyncRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function syncStorageQuoteAction(QuoteSyncRequestTransfer $quoteSyncRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->syncStorageQuote($quoteSyncRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validateQuoteAction(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->validateQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteQuoteAction(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->deleteQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuoteAction(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->createQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuoteAction(QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->updateQuote($quoteUpdateRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateAndReloadQuoteAction(QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->updateAndReloadQuote($quoteUpdateRequestTransfer);
    }
}
