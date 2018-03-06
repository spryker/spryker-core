<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Communication\Controller;

use Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteSyncRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\PersistentCart\Business\PersistentCartFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItemAction(PersistentCartChangeTransfer $persistentCartChangeTransfer)
    {
        return $this->getFacade()->add($persistentCartChangeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItemAction(PersistentCartChangeTransfer $persistentCartChangeTransfer)
    {
        return $this->getFacade()->remove($persistentCartChangeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function reloadItemsAction(QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->reloadItems($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function changeItemQuantityAction(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer)
    {
        return $this->getFacade()->changeItemQuantity($persistentCartChangeQuantityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function decreaseItemQuantityAction(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer)
    {
        return $this->getFacade()->decreaseItemQuantity($persistentCartChangeQuantityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function increaseItemQuantityAction(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer)
    {
        return $this->getFacade()->increaseItemQuantity($persistentCartChangeQuantityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteSyncRequestTransfer $quoteSyncRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function syncStorageQuoteAction(QuoteSyncRequestTransfer $quoteSyncRequestTransfer): QuoteTransfer
    {
        return $this->getFacade()->syncStorageQuote($quoteSyncRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function validateQuoteAction(QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->validateQuote($quoteTransfer);
    }
}
