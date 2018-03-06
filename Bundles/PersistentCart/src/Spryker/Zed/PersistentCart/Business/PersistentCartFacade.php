<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business;

use Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteSyncRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PersistentCart\Business\PersistentCartBusinessFactory getFactory()
 */
class PersistentCartFacade extends AbstractFacade implements PersistentCartFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function add(PersistentCartChangeTransfer $persistentCartChangeTransfer)
    {
        return $this->getFactory()->createCartOperation()->add($persistentCartChangeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function remove(PersistentCartChangeTransfer $persistentCartChangeTransfer)
    {
        return $this->getFactory()->createCartOperation()->remove($persistentCartChangeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function reloadItems(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createCartOperation()->reloadItems($quoteTransfer);
    }

    /**
     * Specification:
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function changeItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer)
    {
        return $this->getFactory()->createCartOperation()->changeItemQuantity($persistentCartChangeQuantityTransfer);
    }

    /**
     * Specification:
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function decreaseItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer)
    {
        return $this->getFactory()->createCartOperation()->decreaseItemQuantity($persistentCartChangeQuantityTransfer);
    }

    /**
     * Specification:
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function increaseItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer)
    {
        return $this->getFactory()->createCartOperation()->increaseItemQuantity($persistentCartChangeQuantityTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteSyncRequestTransfer $quoteSyncRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function syncStorageQuote(QuoteSyncRequestTransfer $quoteSyncRequestTransfer): QuoteTransfer
    {
        return $this->getFactory()->createQuoteStorageSynchronizer()->syncStorageQuote($quoteSyncRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function validateQuote($quoteTransfer)
    {
        return $this->getFactory()->createCartOperation()->validate($quoteTransfer);
    }
}
